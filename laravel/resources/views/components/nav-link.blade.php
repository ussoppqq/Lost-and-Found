 @props(['active' => false])

 @php
 $classes = ($active ?? false)
 ? 'nav-link active'
    : 'nav-link';
    @endphp
 
 <li class="nav-item">
          <a {{ $atributes -> merge (['class' => $classes]) }}>Link</a>
        </li>