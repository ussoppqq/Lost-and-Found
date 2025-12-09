<?php

namespace App\Livewire\Admin\LostAndFound;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Item;
use App\Models\Category;
use App\Models\Post;
use App\Models\ItemPhoto;
use App\Enums\ItemStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditItem extends Component
{
    use WithFileUploads;

    public $itemId;
    public $item;
    
    // Item fields
    public $item_name;
    public $brand;
    public $color;
    public $item_description;
    public $storage;
    public $item_status;
    public $sensitivity_level;
    public $category_id;
    public $post_id;
    
    // Photo management
    public $photos = [];
    public $existingPhotos = [];
    public $photosToDelete = [];
    
    // Modal state
    public $showModal = false;

    protected $listeners = [
        'open-edit-item-modal' => 'openModal',
    ];

    protected function rules()
    {
        return [
            'item_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:100',
            'item_description' => 'nullable|string',
            'storage' => 'nullable|string|max:255',
            'item_status' => 'required|in:REGISTERED,STORED,CLAIMED,DISPOSED,RETURNED',
            'sensitivity_level' => 'required|in:NORMAL,RESTRICTED',
            'category_id' => 'required|exists:categories,category_id',
            'post_id' => 'required|exists:posts,post_id',
            'photos.*' => 'nullable|image|max:2048',
        ];
    }

    public function mount($itemId = null)
    {
        if ($itemId) {
            $this->loadItem($itemId);
        }
    }

    public function loadItem($itemId)
    {
        $this->item = Item::with('photos')->findOrFail($itemId);
        $this->itemId = $itemId;
        
        $this->item_name = $this->item->item_name;
        $this->brand = $this->item->brand;
        $this->color = $this->item->color;
        $this->item_description = $this->item->item_description;
        $this->storage = $this->item->storage;
        $this->item_status = $this->item->item_status;
        $this->sensitivity_level = $this->item->sensitivity_level;
        $this->category_id = $this->item->category_id;
        $this->post_id = $this->item->post_id;
        
        $this->existingPhotos = $this->item->photos->toArray();
    }

    public function openModal($itemId)
    {
        $this->loadItem($itemId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['photos', 'photosToDelete']);
        $this->resetErrorBag();
    }

    public function removeNewPhoto($index)
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function removeExistingPhoto($photoId)
    {
        $this->photosToDelete[] = $photoId;
        $this->existingPhotos = array_filter($this->existingPhotos, function($photo) use ($photoId) {
            return $photo['photo_id'] !== $photoId;
        });
        $this->existingPhotos = array_values($this->existingPhotos);
    }

    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Update item
            $this->item->update([
                'item_name' => $this->item_name,
                'brand' => $this->brand,
                'color' => $this->color,
                'item_description' => $this->item_description,
                'storage' => $this->storage,
                'item_status' => $this->item_status,
                'sensitivity_level' => $this->sensitivity_level,
                'category_id' => $this->category_id,
                'post_id' => $this->post_id,
            ]);

            // Update retention based on new category if changed
            if ($this->item->wasChanged('category_id')) {
                $category = Category::find($this->category_id);
                $this->item->update([
                    'retention_until' => now()->addDays($category->retention_days ?? 30)
                ]);
            }

            // Delete marked photos
            foreach ($this->photosToDelete as $photoId) {
                $photo = ItemPhoto::find($photoId);
                if ($photo) {
                    Storage::disk('public')->delete($photo->photo_url);
                    $photo->delete();
                }
            }

            // Upload new photos
            if (!empty($this->photos)) {
                $maxOrder = ItemPhoto::where('item_id', $this->item->item_id)->max('display_order') ?? -1;
                
                foreach ($this->photos as $index => $photo) {
                    $path = $photo->store('item-photos', 'public');
                    
                    ItemPhoto::create([
                        'photo_id' => (string) Str::uuid(),
                        'company_id' => $this->item->company_id,
                        'item_id' => $this->item->item_id,
                        'photo_url' => $path,
                        'alt_text' => $this->item_name . ' - Photo',
                        'display_order' => $maxOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Item updated successfully!');
            $this->closeModal();
            $this->dispatch('item-updated');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('item_name', 'Error updating item: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;
        $categories = Category::where('company_id', $companyId)->get();
        $posts = Post::where('company_id', $companyId)->get();

        return view('livewire.admin.lost-and-found.edit-item', [
            'categories' => $categories,
            'posts' => $posts,
            'statusOptions' => ItemStatus::options(),
        ]);
    }
}