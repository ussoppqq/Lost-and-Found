<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Map Pin Mapper</title>
    <style>
        body { font-family: ui-sans-serif, system-ui; margin: 0; background:#0b0f19; color:#e5e7eb; }
        .wrap { display:flex; gap:16px; padding:16px; }
        .panel { width: 380px; min-width: 320px; background:#111827; border:1px solid #243041; border-radius:12px; padding:12px; }
        .map-box { position:relative; flex:1; background:#000; border-radius:12px; overflow:hidden; border:1px solid #243041; }
        .map-box img { width:100%; height:auto; display:block; user-select:none; -webkit-user-drag:none; }
        .dot { position:absolute; width:10px; height:10px; border-radius:999px; background:#22c55e; transform:translate(-50%,-50%); box-shadow:0 0 0 2px rgba(0,0,0,.5); }
        textarea { width:100%; height:220px; background:#0b1220; color:#e5e7eb; border:1px solid #243041; border-radius:10px; padding:10px; }
        input, select, button { width:100%; padding:10px; border-radius:10px; border:1px solid #243041; background:#0b1220; color:#e5e7eb; }
        button { cursor:pointer; background:#1f2937; }
        .row { display:flex; gap:10px; }
        .row > * { flex:1; }
        .hint { font-size:12px; color:#9ca3af; line-height:1.4; }
        .hr { height:1px; background:#243041; margin:10px 0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="panel">
        <h3 style="margin:0 0 8px;">Pin Mapper</h3>
        <div class="hint">
            1) Isi ID pin (1–57) + kategori.<br>
            2) Klik tepat di bulatan angka pada peta.<br>
            3) Copy output JSON/PHP ke MapLegendData.
        </div>

        <div class="hr"></div>

        <div class="row">
            <input id="pinId" type="number" min="1" max="57" placeholder="ID (1-57)">
            <select id="category">
                <option value="facility">facility</option>
                <option value="avenue">avenue</option>
                <option value="site">site</option>
                <option value="collection">collection</option>
            </select>
        </div>

        <div style="margin-top:10px;" class="row">
            <input id="name" placeholder="Nama (ID)">
            <input id="subtitle" placeholder="Subtitle (EN)">
        </div>

        <div style="margin-top:10px;" class="row">
            <select id="color">
                <option value="37, 99, 235">Facilities (blue)</option>
                <option value="249, 115, 22">Avenues (orange)</option>
                <option value="147, 51, 234">Sites (purple)</option>
                <option value="22, 163, 74">Collections (green)</option>
            </select>
            <button id="undoBtn" type="button">Undo terakhir</button>
        </div>

        <div style="margin-top:10px;" class="row">
            <button id="copyJson" type="button">Copy JSON</button>
            <button id="copyPhp" type="button">Copy PHP array</button>
        </div>

        <div style="margin-top:10px;">
            <textarea id="out" readonly placeholder="Output akan muncul di sini..."></textarea>
        </div>

        <div class="hint">
            Tips: isi dulu name/subtitle sesuai legend kamu (biar rapih), lalu klik.
        </div>
    </div>

    <div class="map-box" id="mapBox">
        {{-- Ganti path ini sesuai lokasi file gambar di public/ --}}
        <img id="mapImg" src="{{ asset('images/peta-kebun-raya-bogor.png') }}" alt="Map">
    </div>
</div>

<script>
    const mapBox = document.getElementById('mapBox');
    const mapImg = document.getElementById('mapImg');
    const out = document.getElementById('out');

    const pinId = document.getElementById('pinId');
    const category = document.getElementById('category');
    const name = document.getElementById('name');
    const subtitle = document.getElementById('subtitle');
    const color = document.getElementById('color');

    const undoBtn = document.getElementById('undoBtn');
    const copyJson = document.getElementById('copyJson');
    const copyPhp = document.getElementById('copyPhp');

    let pins = [];
    let dots = [];

    function renderOut() {
        out.value = JSON.stringify(pins, null, 2);
    }

    function addDot(leftPct, topPct) {
        const d = document.createElement('div');
        d.className = 'dot';
        d.style.left = leftPct + '%';
        d.style.top  = topPct + '%';
        mapBox.appendChild(d);
        dots.push(d);
    }

    mapBox.addEventListener('click', (e) => {
        const idVal = parseInt(pinId.value, 10);
        if (!idVal || idVal < 1 || idVal > 57) {
            alert('Isi ID 1-57 dulu ya.');
            return;
        }

        const rect = mapImg.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const leftPct = (x / rect.width) * 100;
        const topPct  = (y / rect.height) * 100;

        const item = {
            id: idVal,
            name: name.value || `Pin ${idVal}`,
            subtitle: subtitle.value || '',
            top: topPct.toFixed(2) + '%',
            left: leftPct.toFixed(2) + '%',
            color: color.value,
            category: category.value,
        };

        pins.push(item);
        addDot(item.left, item.top);
        renderOut();
    });

    undoBtn.addEventListener('click', () => {
        pins.pop();
        const d = dots.pop();
        if (d) d.remove();
        renderOut();
    });

    copyJson.addEventListener('click', async () => {
        await navigator.clipboard.writeText(out.value);
        alert('Copied JSON ✅');
    });

    copyPhp.addEventListener('click', async () => {
        const php = pins.map(p => {
            return `['id' => ${p.id}, 'name' => '${p.name.replaceAll("'", "\\'")}', 'subtitle' => '${p.subtitle.replaceAll("'", "\\'")}', 'top' => '${p.top}', 'left' => '${p.left}', 'color' => '${p.color}', 'category' => '${p.category}'],`;
        }).join("\n");
        await navigator.clipboard.writeText(php);
        alert('Copied PHP array ✅');
    });

    renderOut();
</script>
</body>
</html>
