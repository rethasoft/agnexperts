@extends('app.layouts.app')
@section('title', 'Documents')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Documents</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Left sidebar: folder structure -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Mappenstructuur</h5>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="ri-add-line"></i> Nieuwe Map
                        </a>
                    </div>

                    <div class="folder-search mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" class="form-control" id="folderSearch" placeholder="Zoek map...">
                        </div>
                    </div>

                    <div class="folder-tree border rounded p-2 bg-light">
                        <ul class="list-unstyled mb-0 folder-root">
                            <li class="folder-item mb-1">
                                <a href="{{ route('documents.index') }}"
                                    class="folder-link d-flex align-items-center px-2 py-1 rounded text-decoration-none {{ !isset($_GET['folder_id']) ? 'bg-primary text-white' : 'text-dark' }}">
                                    <i class="ri-home-4-line me-2"></i>
                                    <span>Hoofdmap</span>
                                </a>
                            </li>

                            @if (isset($allFolders) && count($allFolders) > 0)
                                @php
                                    function renderFoldersBootstrap($folders, $level = 0, $activeFolderId = null)
                                    {
                                        // Keep track of whether any child folder is active
                                        $hasActiveChild = false;

                                        $output = '<ul class="list-unstyled ps-3 folder-children">';

                                        foreach ($folders as $folder) {
                                            // Check if this folder is directly active
                                            $isActive = isset($_GET['folder_id']) && $_GET['folder_id'] == $folder->id;

                                            // Check if any children are active (recursive)
                                            $childrenActive = false;
                                            $childrenHtml = '';

                                            if ($folder->children && $folder->children->count()) {
                                                ob_start();
                                                $childrenActive = renderFoldersBootstrap(
                                                    $folder->children,
                                                    $level + 1,
                                                    $activeFolderId,
                                                );
                                                $childrenHtml = ob_get_clean();
                                            }

                                            // This folder should be highlighted if it's active OR if any of its children are active
        $isHighlighted = $isActive || $childrenActive;
        $hasActiveChild = $hasActiveChild || $isHighlighted;

        $activeClass = $isActive
            ? 'bg-primary text-white'
            : ($isHighlighted
                ? 'bg-light fw-normal'
                : 'text-dark');
        $expandState = $isHighlighted ? 'true' : 'false';

        $output .=
            '<li class="folder-item mb-1 ' .
            ($isHighlighted ? 'folder-highlighted' : '') .
            '">';

        $output .= '<div class="d-flex align-items-center position-relative">';

        // Expandable toggle button if has children
        if ($folder->children && $folder->children->count()) {
            $output .=
                '<button class="btn btn-sm btn-link text-decoration-none p-0 me-1 folder-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#folder-' .
                $folder->id .
                '" aria-expanded="' .
                $expandState .
                '">';
            $output .= '<i class="ri-arrow-right-s-line"></i>';
            $output .= '</button>';
        } else {
            $output .= '<span class="ps-3"></span>';
        }

        // Folder link
        $output .=
            '<a href="' .
            route('documents.index', ['folder_id' => $folder->id]) .
            '" class="folder-link flex-grow-1 d-flex align-items-center px-2 py-1 rounded text-decoration-none ' .
            $activeClass .
            '">';
        $output .=
            '<i class="ri-folder-line me-2 ' .
            ($isActive ? '' : 'text-warning') .
            '"></i>';
        $output .= '<span>' . $folder->name . '</span>';

        // Badge for document count (optional)
        if (isset($folder->documents_count)) {
            $output .=
                '<span class="ms-auto badge rounded-pill ' .
                ($isActive ? 'bg-white text-primary' : 'bg-light text-dark') .
                '">' .
                $folder->documents_count .
                '</span>';
        }

        $output .= '</a>';

        // Add folder actions (in a btn-group)
        $output .= '<div class="ms-2 btn-group shadow-sm folder-actions">';

        // Add child folder button
        $output .=
            '<a href="' .
            route('documents.create', ['parent_id' => $folder->id]) .
            '" class="btn btn-sm btn-success" ' .
            'style="border-radius: 6px 0 0 0; transition: all 0.2s ease;" ' .
            'title="Submap toevoegen">';
        $output .= '<i class="ri-add-line"></i>';
        $output .= '</a>';

        // Edit button
        $output .=
            '<a href="' .
            route('documents.edit', $folder->id) .
            '" class="btn btn-sm btn-info" ' .
            'style="border-radius: 0; transition: all 0.2s ease;" ' .
            'title="Bewerk map">';
        $output .= '<i class="ri-pencil-line"></i>';
        $output .= '</a>';

        // Delete button (with confirmation and embedded form)
        $output .=
            '<form id="delete-folder-' .
            $folder->id .
            '" action="' .
            route('documents.destroy', $folder->id) .
            '" method="POST" style="display:inline;">';
        $output .=
            csrf_field() .
            '<input type="hidden" name="_method" value="DELETE"><input type="hidden" name="is_folder" value="1">';
        $output .=
            '<button type="button" class="btn btn-sm btn-danger" ' .
            'style="border-radius: 0 6px 0 0; transition: all 0.2s ease;" ' .
            'title="Verwijder map" ' .
            'onclick="if(confirm(\'Weet je zeker dat je de map ' .
            addslashes($folder->name) .
            ' wilt verwijderen?\')) document.getElementById(\'delete-folder-' .
            $folder->id .
            '\').submit();">';
        $output .= '<i class="ri-delete-bin-line"></i>';
        $output .= '</button></form>';

        $output .= '</div>'; // End btn-group folder-actions

        $output .= '</div>'; // End d-flex

        // Render children if any
        if ($folder->children && $folder->children->count()) {
            $output .=
                '<div class="collapse ' .
                ($isHighlighted ? 'show' : '') .
                '" id="folder-' .
                $folder->id .
                '">';
            $output .= $childrenHtml;
            $output .= '</div>';
        }

        $output .= '</li>';
    }

    $output .= '</ul>';

                                        echo $output;

                                        // Return whether this subtree has any active folders (important for recursion)
                                        return $hasActiveChild;
                                    }
                                @endphp

                                {{-- Using the curly braces WITHOUT !! prevents the function's return value from being echoed --}}
                                @php
                                    // Execute the function but don't output its return value
renderFoldersBootstrap($allFolders, 0, request('folder_id'));
                                @endphp
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-folder-add-line d-block mb-2" style="font-size: 2rem; color: #ccc;"></i>
                                    <p class="text-muted">Geen mappen gevonden</p>
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side: documents and current folder view -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
                    @endif
                    @if ($msg = Session::get('msg'))
                        <div class="alert alert-success mt-3">{{ $msg }}</div>
                    @endif


                    @if (isset($currentFolder))
                        <nav aria-label="folder navigation" class="mb-3">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Root</a></li>
                                @foreach ($breadcrumbs ?? [] as $breadcrumb)
                                    <li class="breadcrumb-item">
                                        <a
                                            href="{{ route('documents.index', ['folder_id' => $breadcrumb->id]) }}">{{ $breadcrumb->name }}</a>
                                    </li>
                                @endforeach
                                @if ($currentFolder)
                                    <li class="breadcrumb-item active">{{ $currentFolder->name }}</li>
                                @endif
                            </ol>
                        </nav>
                    @endif

                    <!-- Subfolder cards (if in a folder with subfolders) -->
                    @if (isset($subFolders) && count($subFolders) > 0)
                        <h6 class="mt-4 mb-3">Submappen</h6>
                        <div class="row">
                            @foreach ($subFolders as $subFolder)
                                <div class="col-md-4 mb-3">
                                    <a href="{{ route('documents.index', ['folder_id' => $subFolder->id]) }}"
                                        class="text-decoration-none">
                                        <div class="card h-100 folder-card">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-folder-line text-warning fs-4 me-2"></i>
                                                    <div class="folder-info">
                                                        <h6 class="mb-0 text-truncate">{{ $subFolder->name }}</h6>
                                                        @if (isset($subFolder->documents_count))
                                                            <small class="text-muted">{{ $subFolder->documents_count }}
                                                                documenten</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <h5 class="mb-3 mt-4">Documenten in map</h5>
                    <div class="table table-striped">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Bestandsnaam</th>
                                    <th>Geüpload op</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($documents) && count($documents) > 0)
                                    @foreach ($documents as $document)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if (in_array($document->file_type, ['image/png', 'image/jpg', 'image/jpeg']))
                                                        <i class="ri-image-line text-primary fs-5 me-2"></i>
                                                    @elseif ($document->file_type == 'application/pdf')
                                                        <i class="ri-file-pdf-line text-danger fs-5 me-2"></i>
                                                    @else
                                                        <i class="ri-file-line text-muted fs-5 me-2"></i>
                                                    @endif
                                                    <span class="document-name">{{ $document->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $document->created_at->format('d-m-Y') }}</td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('documents.download', encrypt($document->id)) }}">
                                                                <i class="ri-download-line me-2"></i> Download
                                                            </a>
                                                        </li>
                                                        {{-- <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('documents.edit', $document->id) }}">
                                                                <i class="ri-pencil-line me-2"></i> Bewerken
                                                            </a>
                                                        </li> --}}
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="event.preventDefault(); if(confirm('Weet je zeker dat je dit document wilt verwijderen?')) document.getElementById('delete-doc-{{ $document->id }}').submit();">
                                                                <i class="ri-delete-bin-line me-2"></i> Verwijderen
                                                            </a>
                                                            <form id="delete-doc-{{ $document->id }}"
                                                                action="{{ route('documents.destroy', encrypt($document->id)) }}"
                                                                method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">
                                            <div class="alert alert-info mb-0">
                                                <i class="ri-information-line me-2"></i>
                                                Geen documenten gevonden in deze map.
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .folder-tree {
            max-height: 500px;
            overflow-y: auto;
        }

        .folder-root>.folder-item>.folder-children {
            border-left: 1px dashed #dee2e6;
            margin-left: 0.5rem;
        }

        .folder-children {
            border-left: 1px dashed #dee2e6;
            margin-left: 0.5rem !important;
            padding-left: 0.5rem !important;
        }

        .folder-link {
            transition: all 0.2s ease;
        }

        .folder-link:hover {
            background-color: #f1f3f5;
        }

        .folder-toggle {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 1.5rem;
            color: #6c757d;
        }

        .folder-toggle i {
            transition: transform 0.2s;
        }

        .folder-toggle[aria-expanded="true"] i {
            transform: rotate(90deg);
        }

        .folder-card {
            transition: all 0.2s ease;
            border: 1px solid #dee2e6;
        }

        .folder-card:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        .document-name {
            max-width: 300px;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .folder-highlighted>div>.folder-link {
            font-weight: 500;
        }

        .folder-highlighted {
            position: relative;
        }

        /* Better visual indicator for parent paths */
        .folder-highlighted::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #0d6efd;
            opacity: 0.5;
        }

        /* Keep active folder more prominent */
        .folder-link.bg-primary {
            font-weight: 600;
        }

        /* Make folders with active children more visible */
        .folder-toggle[aria-expanded="true"] i {
            transform: rotate(90deg);
            color: #0d6efd;
        }

        /* Folder actions (edit/delete) */
        .folder-actions {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }

        .folder-item:hover .folder-actions {
            display: flex;
        }

        .folder-action-btn {
            padding: 0.25rem;
            font-size: 0.75rem;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize folder tree toggles
            document.querySelectorAll('.folder-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    if (this.getAttribute('aria-expanded') === 'true') {
                        icon.style.transform = 'rotate(90deg)';
                    } else {
                        icon.style.transform = 'rotate(0)';
                    }
                });

                // Set initial state
                if (toggle.getAttribute('aria-expanded') === 'true') {
                    toggle.querySelector('i').style.transform = 'rotate(90deg)';
                }
            });

            // Folder search functionality
            const searchInput = document.getElementById('folderSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    // Add a clear button when there's search text
                    if (searchTerm.length > 0) {
                        if (!document.getElementById('clearSearch')) {
                            const clearBtn = document.createElement('button');
                            clearBtn.id = 'clearSearch';
                            clearBtn.className = 'btn btn-sm btn-outline-secondary position-absolute end-0';
                            clearBtn.style.zIndex = '5';
                            clearBtn.innerHTML = '<i class="ri-close-line"></i>';
                            clearBtn.onclick = function() {
                                searchInput.value = '';
                                searchInput.dispatchEvent(new Event('input'));
                                this.remove();
                            };
                            searchInput.parentNode.style.position = 'relative';
                            searchInput.parentNode.appendChild(clearBtn);
                        }
                    } else if (document.getElementById('clearSearch')) {
                        document.getElementById('clearSearch').remove();
                    }

                    // First reset everything to initial state if search is empty
                    if (searchTerm === '') {
                        resetFolderView();
                        return;
                    }

                    // Get ALL folder items, including those in all root folders
                    // Use more specific selector to target the items we want
                    const allFolderItems = document.querySelectorAll('.folder-tree .folder-item');
                    let anyVisible = false;

                    // Keep track of which folders have matches (either directly or in children)
                    const foldersWithMatches = new Set();

                    // First pass: Check which folders match the search term directly
                    allFolderItems.forEach(item => {
                        // Skip the "Hoofdmap" root item - it should always remain visible
                        if (item.querySelector('.ri-home-4-line')) {
                            return;
                        }

                        const folderLink = item.querySelector('.folder-link');
                        if (!folderLink) return;

                        const folderNameSpan = folderLink.querySelector('span');
                        if (!folderNameSpan) return;

                        const folderName = folderNameSpan.textContent.toLowerCase();
                        const matches = folderName.includes(searchTerm);

                        if (matches) {
                            // This folder matches directly - mark all its parents
                            markFolderAndParents(item, foldersWithMatches);
                            anyVisible = true;

                            // Highlight the matching text
                            const regex = new RegExp(
                                `(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                            folderNameSpan.innerHTML = folderName.replace(regex, '<mark>$1</mark>');
                        } else {
                            // Reset the folder name if it doesn't match
                            folderNameSpan.innerHTML = folderName;
                        }
                    });

                    // Second pass: Show/hide folders based on matches
                    allFolderItems.forEach(item => {
                        // Skip the "Hoofdmap" root item - it should always remain visible
                        if (item.querySelector('.ri-home-4-line')) {
                            return;
                        }

                        if (foldersWithMatches.has(item)) {
                            item.style.display = '';

                            // Make sure all parent folders are expanded
                            expandParentFolders(item);
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    const noResultsMsg = document.getElementById('noSearchResults');
                    if (noResultsMsg) {
                        if (!anyVisible) {
                            noResultsMsg.style.display = 'block';
                        } else {
                            noResultsMsg.style.display = 'none';
                        }
                    }
                });
            }

            // Function to mark a folder and all its parent folders for display
            function markFolderAndParents(folderElement, markedSet) {
                // Mark this folder
                markedSet.add(folderElement);

                // Find and mark all parent folders
                let parent = folderElement.parentElement; // This is the ul.folder-children
                if (parent && parent.classList.contains('folder-children')) {
                    // Go up to the parent li.folder-item
                    parent = parent.closest('.folder-item');
                    if (parent && parent !== folderElement) {
                        // Mark the parent folder
                        markFolderAndParents(parent, markedSet);
                    }
                }

                // Also check if it's in another UL that might be a different root
                const rootParent = folderElement.closest('ul.folder-children');
                if (rootParent && rootParent.parentElement.classList.contains('folder-tree')) {
                    markedSet.add(rootParent.parentElement);
                }
            }

            // Helper function to expand all parent folders of an element
            function expandParentFolders(element) {
                let parent = element.closest('.collapse');
                while (parent) {
                    // Show this collapsible section
                    parent.classList.add('show');

                    // Find the toggle button and update its state
                    const toggleId = parent.id;
                    const toggle = document.querySelector(`[data-bs-target="#${toggleId}"]`);
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'true');
                        const icon = toggle.querySelector('i');
                        if (icon) icon.style.transform = 'rotate(90deg)';
                    }

                    // Move up to the next parent folder
                    parent = parent.parentElement.closest('.collapse');
                }
            }

            // Function to reset the folder view to its initial state
            function resetFolderView() {
                const allFolderItems = document.querySelectorAll('.folder-item:not(:first-child)');

                // Show all folders
                allFolderItems.forEach(item => {
                    // Make all folders visible first
                    item.style.display = '';

                    // Reset folder name text (remove highlights)
                    const folderNameSpan = item.querySelector('.folder-link span');
                    if (folderNameSpan) {
                        folderNameSpan.innerHTML = folderNameSpan.textContent;
                    }

                    // Reset expansion state - collapse all except highlighted path
                    const isHighlighted = item.classList.contains('folder-highlighted');
                    const collapseElement = item.querySelector('.collapse');

                    if (collapseElement && !isHighlighted) {
                        collapseElement.classList.remove('show');

                        const toggleButton = item.querySelector('.folder-toggle');
                        if (toggleButton) {
                            toggleButton.setAttribute('aria-expanded', 'false');
                            const icon = toggleButton.querySelector('i');
                            if (icon) icon.style.transform = 'rotate(0)';
                        }
                    }
                });

                // Re-expand folders in the active path
                document.querySelectorAll('.folder-highlighted').forEach(highlightedFolder => {
                    expandParentFolders(highlightedFolder);
                });

                // Hide no results message
                const noResultsMsg = document.getElementById('noSearchResults');
                if (noResultsMsg) {
                    noResultsMsg.style.display = 'none';
                }
            }

            // Fix for chart-related errors 
            if (typeof Chart !== 'undefined' && document.getElementById('performanceTrendChart') === null) {
                // Create a dummy canvas element
                const dummyCanvas = document.createElement('canvas');
                dummyCanvas.id = 'performanceTrendChart';
                dummyCanvas.style.display = 'none';
                document.body.appendChild(dummyCanvas);

                // Also create skillDistributionChart if needed
                if (document.getElementById('skillDistributionChart') === null) {
                    const dummySkillCanvas = document.createElement('canvas');
                    dummySkillCanvas.id = 'skillDistributionChart';
                    dummySkillCanvas.style.display = 'none';
                    document.body.appendChild(dummySkillCanvas);
                }
            }
        });
    </script>
@endpush
