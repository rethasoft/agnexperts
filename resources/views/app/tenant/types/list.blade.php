@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Dienst</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
                    @endif
                    @if ($msg = Session::get('msg'))
                        <div class="alert alert-success mt-3">{{ $msg }}</div>
                    @endif
                    <div class="button-bar mb-2">
                        <a href="{{ route('dienst.create') }}" class="btn btn-success"><i class="ri-add-line"></i></a>
                    </div>
                    <table class="table table-striped" id="types-table">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Korte Naam</th>
                                <th>Prijs</th>
                                <th>Extra Prijs</th>
                                <th>Regio's</th>
                                <th>Datum</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="top-dropzone" class="parent-dropzone">
                            <tr>
                                <td colspan="7" class="text-muted small">Sleep buraya bırakırsan yeni Ana Hizmet olur</td>
                            </tr>
                        </tbody>
                        @if ($data->count() > 0)
                            @foreach ($data as $item)
                            <tbody class="parent-group" data-parent="{{ $item->id }}">
                                <tr data-id="{{ $item->id }}" class="parent-row">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->short_name }}</td>
                                    <td>{{ $item->formatted_price }}</td>
                                    <td>{{ $item->formatted_extra_price }}</td>
                                    <td>
                                        @if($item->regions)
                                            @foreach($item->regions as $region)
                                                <span class="badge bg-primary me-1">{{ ucfirst($region) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Geen regio's</span>
                                        @endif
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <form action="{{ route('dienst.edit', ['dienst' => $item->id]) }}" class="d-inline-block" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                        </form>
                                        <form action="{{ route('dienst.destroy', ['dienst' => $item->id]) }}" class="d-inline-block" method="POST"
                                            onsubmit="if(!confirm('Do you want to delete this record')){return false;}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @if ($item->subTypes->count() > 0)
                                    @foreach ($item->subTypes as $type)
                                    <tr data-id="{{ $type->id }}" class="child-row">
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;-- {{ $type->name }}</td>
                                        <td>{{ $type->short_name }}</td>
                                        <td>{{ $type->formatted_price }}</td>
                                        <td>{{ $type->formatted_extra_price }}</td>
                                        <td>
                                            @if($type->regions)
                                                @foreach($type->regions as $region)
                                                    <span class="badge bg-primary me-1">{{ ucfirst($region) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Geen regio's</span>
                                            @endif
                                        </td>
                                        <td>{{ date('Y-m-d', strtotime($type->created_at)) }}</td>
                                        <td>
                                            <form action="{{ route('dienst.edit', ['dienst' => $type->id]) }}" class="d-inline-block" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                            </form>
                                            <form action="{{ route('dienst.destroy', ['dienst' => $type->id]) }}" class="d-inline-block" method="POST"
                                                onsubmit="if(!confirm('Do you want to delete this record')){return false;}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            @endforeach
                        @else
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <div class="alert alert-danger mb-0">{{ __('validation.custom.no_data') }}</div>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<style>
    .mini-toast{position:fixed;right:16px;bottom:16px;z-index:1080;min-width:220px;max-width:320px;border-radius:6px;padding:10px 14px;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,.15);font-size:14px}
    .mini-toast.success{background:#198754}
    .mini-toast.error{background:#dc3545}
    .mini-toast.fade-out{opacity:0;transition:opacity .4s ease}
 </style>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function notify(message, type){
        try{
            const el = document.createElement('div');
            el.className = 'mini-toast ' + (type==='error'?'error':'success');
            el.textContent = message;
            document.body.appendChild(el);
            setTimeout(()=>{ el.classList.add('fade-out'); }, 1600);
            setTimeout(()=>{ el.remove(); }, 2000);
        }catch(e){ /* no-op */ }
    }

    let sending = false;
    const postOrders = async (orders) => {
        if (!orders.length) return;
        sending = true;
        await fetch("{{ route('dienst.sort') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ orders })
        }).then(function(res){
            if(!res.ok){ throw new Error('save failed'); }
        }).catch(function(){ notify('Er is een fout opgetreden','error'); })
        .finally(function(){ sending = false; });
    };

    // Parent gruplar arası sıralamayı kaydet (sadece parent-row, category_id=0)
    const sendGroupOrder = async () => {
        if (sending) return;
        const groups = document.querySelectorAll('#types-table > tbody.parent-group');
        const orders = [];
        groups.forEach((group, idx) => {
            const parentRow = group.querySelector('tr.parent-row[data-id]');
            if (parentRow){ orders.push({ id: parentRow.dataset.id, sort_order: idx, category_id: 0 }); }
        });
        await postOrders(orders);
    };

    // Bir grubun içindeki parent+child sıralamasını kaydet
    // Parent satır da listede ama onun category_id'si her zaman 0 kalmalı.
    const sendItemsOrder = async (groupTbody) => {
        if (sending) return;
        const rows = groupTbody.querySelectorAll('tr[data-id]');
        const parentIdForChildren = parseInt(groupTbody.dataset.parent, 10);
        const orders = [];
        rows.forEach((row, idx) => {
            const isParent = row.classList.contains('parent-row');
            const categoryId = isParent ? 0 : parentIdForChildren;
            orders.push({ id: row.dataset.id, sort_order: idx, category_id: categoryId });
        });
        await postOrders(orders);
    };

    const initialized = new WeakSet();
    const dragState = { isParentDrag: false, attachedChildren: [], fromGroup: null, originalContainer: null, originalNextSibling: null };
    const sortables = [];
    const setAllDisabled = (disabled) => {
        sortables.forEach(function(s){ try{ s.option('disabled', disabled); } catch(e){} });
    };

    const initSubtypesSortable = (tbody) => {
        if (!tbody || initialized.has(tbody)) return;
        const s = new Sortable(tbody, {
            animation: 150,
            handle: 'td',
            draggable: 'tr.child-row, tr.parent-row',
            group: { name: 'types', pull: true, put: true },
            fallbackOnBody: true,
            forceFallback: true,
            dragoverBubble: true,
            swapThreshold: 0.65,
            emptyInsertThreshold: 10,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            filter: 'a, button, .btn, input, form',
            onStart: function(evt){
                dragState.isParentDrag = evt.item.classList.contains('parent-row');
                dragState.attachedChildren = [];
                dragState.fromGroup = (evt.from && evt.from.closest) ? evt.from.closest('tbody.parent-group') : evt.from;
                dragState.originalContainer = evt.from;
                dragState.originalNextSibling = evt.item.nextElementSibling;
                if (dragState.isParentDrag) {
                    // Toplayacağımız çocuklar: bir sonraki parent-row'a kadar olan child-row'lar
                    let cursor = evt.item.nextElementSibling;
                    while (cursor && !cursor.classList.contains('parent-row')) {
                        if (cursor.classList.contains('child-row')) {
                            dragState.attachedChildren.push(cursor);
                        }
                        cursor = cursor.nextElementSibling;
                    }
                }
            },
            onAdd: async function(evt){
                try{
                    setAllDisabled(true);
                    const to = (evt.to && evt.to.closest) ? (evt.to.closest('tbody.parent-group') || evt.to) : evt.to;
                    const from = (evt.from && evt.from.closest) ? (evt.from.closest('tbody.parent-group') || evt.from) : evt.from;
                    // Kural: Altı dolu olan parent başka parent altına taşınamaz (2 seviye kuralını koru)
                    if (dragState.isParentDrag && dragState.attachedChildren.length && to !== from) {
                        // Hamleyi geri al
                        if (dragState.originalContainer) {
                            if (dragState.originalNextSibling) {
                                dragState.originalContainer.insertBefore(evt.item, dragState.originalNextSibling);
                            } else {
                                dragState.originalContainer.appendChild(evt.item);
                            }
                        }
                        notify('Önce bu ana hizmetin altındakileri boşaltın', 'error');
                        return;
                    }
                    // Eğer bir parent başka gruba taşındıysa, çocuklarını da yeni gruba taşı
                    if (dragState.isParentDrag && to !== from) {
                        // Önce hedefte parent-row'u bul ve bırakılanı onun hemen altına yerleştir
                        const targetParent = to.querySelector('tr.parent-row');
                        if (targetParent) {
                            to.insertBefore(evt.item, targetParent.nextSibling);
                        }
                        // Taşınan parent artık child olmalı
                        evt.item.classList.remove('parent-row');
                        evt.item.classList.add('child-row');
                        // Görsel: İlk hücrede child biçimini hemen uygula ("-- " öneki ve boşluklar)
                        const firstTd = evt.item.querySelector('td');
                        if (firstTd) {
                            const raw = firstTd.textContent.trim();
                            if (!raw.startsWith('--')) {
                                firstTd.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;-- ' + raw;
                            }
                        }
                        // Eski grup boş kaldıysa DOM'dan kaldır
                        if (dragState.fromGroup && dragState.fromGroup.querySelectorAll('tr').length === 0) {
                            const table = document.getElementById('types-table');
                            if (dragState.fromGroup.parentNode === table) {
                                table.removeChild(dragState.fromGroup);
                            } else {
                                dragState.fromGroup.remove();
                            }
                        }
                    }
                    // Güvenlik: Hedef grupta parent-row'u en üstte tut
                    const ensureParentOnTop = () => {
                        const p = to.querySelector('tr.parent-row');
                        if (p && to.firstElementChild !== p) {
                            to.insertBefore(p, to.firstElementChild);
                        }
                    };
                    ensureParentOnTop();
                    // Eğer bir child, parent'ın üstüne taşındıysa: parent seviyesine terfi ettir
                    const parentRow = to.querySelector('tr.parent-row');
                    if (evt.item.classList.contains('child-row') && parentRow) {
                        const rows = Array.from(to.querySelectorAll('tr[data-id]'));
                        const parentIdx = rows.indexOf(parentRow);
                        const itemIdx = rows.indexOf(evt.item);
                        if (itemIdx > -1 && parentIdx > -1 && itemIdx < parentIdx) {
                            const table = document.getElementById('types-table');
                            const newGroup = document.createElement('tbody');
                            newGroup.className = 'parent-group';
                            newGroup.dataset.parent = evt.item.dataset.id;
                            // Promote to parent
                            evt.item.classList.remove('child-row');
                            evt.item.classList.add('parent-row');
                            const firstTd = evt.item.querySelector('td');
                            if (firstTd) {
                                const raw = firstTd.textContent.replace(/^\s+|\s+$/g,'');
                                firstTd.textContent = raw.replace(/^--\s*/, '');
                            }
                            newGroup.appendChild(evt.item);
                            table.insertBefore(newGroup, to);
                            initSubtypesSortable(newGroup);
                            await sendItemsOrder(newGroup);
                            await sendGroupOrder();
                            if (from && from !== to) { await sendItemsOrder(from); }
                            notify('Volgorde bijgewerkt','success');
                            return; // bu akışta kalan işlemleri atla
                        }
                    }

                    await sendItemsOrder(to);
                    if (from && from !== to) { await sendItemsOrder(from); }
                    notify('Volgorde bijgewerkt','success');
                } finally { setAllDisabled(false); }
            },
            onUpdate: async function(evt){
                try{
                    setAllDisabled(true);
                    // Hedef grupta parent satır en üstte kalsın
                    const to = (evt.to && evt.to.closest) ? (evt.to.closest('tbody.parent-group') || evt.to) : evt.to;
                    const p = to.querySelector('tr.parent-row');
                    if (p && to.firstElementChild !== p) { to.insertBefore(p, to.firstElementChild); }
                    // Child, parent'ın üstüne çıkarıldıysa terfi ettir
                    if (evt.item.classList.contains('child-row') && p) {
                        const rows = Array.from(to.querySelectorAll('tr[data-id]'));
                        const parentIdx = rows.indexOf(p);
                        const itemIdx = rows.indexOf(evt.item);
                        if (itemIdx > -1 && parentIdx > -1 && itemIdx < parentIdx) {
                            const table = document.getElementById('types-table');
                            const newGroup = document.createElement('tbody');
                            newGroup.className = 'parent-group';
                            newGroup.dataset.parent = evt.item.dataset.id;
                            evt.item.classList.remove('child-row');
                            evt.item.classList.add('parent-row');
                            const firstTd = evt.item.querySelector('td');
                            if (firstTd) {
                                const raw = firstTd.textContent.replace(/^\s+|\s+$/g,'');
                                firstTd.textContent = raw.replace(/^--\s*/, '');
                            }
                            newGroup.appendChild(evt.item);
                            table.insertBefore(newGroup, to);
                            initSubtypesSortable(newGroup);
                            await sendItemsOrder(newGroup);
                            await sendGroupOrder();
                            notify('Volgorde bijgewerkt','success');
                            return;
                        }
                    }

                    await sendItemsOrder(evt.to);
                    notify('Volgorde bijgewerkt','success');
                } finally { setAllDisabled(false); }
            }
        });
        sortables.push(s);
        initialized.add(tbody);
    };

    const ensureChildContainerForParentRow = (row) => {
        if (!row || row.tagName !== 'TR') return;
        const group = row.closest('tbody.parent-group');
        if (!group) return;
        // parent-group zaten parent + çocukları barındırır; ekstra alt tbody gerekmiyor
    };

    // Her parent-group kendi içinde sortable; parent'ı başka gruba sürüklemek doğrudan o gruba bırakılarak yapılır

    document.querySelectorAll('tbody.parent-group').forEach(function(group){
        initSubtypesSortable(group);
    });
});
</script>
@endpush
@endsection
