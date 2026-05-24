{{-- 
    Usage:
    @include('components.gallery-tab', [
        'entityId'   => $doctor->id,
        'entityType' => 'doctor',
        'uploadRoute'=> route('gallery.upload'),
        'deleteRoute'=> route('gallery.delete'),
        'imagesRoute'=> route('gallery.images'),
    ])
--}}

<!-- Dropzone CSS -->
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">

<div class="gallery-section">
    <h5 class="mb-3"><i class="fa fa-images mr-2"></i> Gallery Images</h5>

    <!-- Dropzone Upload Area -->
    <form id="galleryDropzone_{{ $entityType }}" class="dropzone" style="border: 2px dashed #13C5DD; border-radius: 8px; background: #f9f9f9;">
        @csrf
        <div class="dz-message">
            <i class="fa fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
            <p class="mb-0">Click or drag images here to upload</p>
            <small class="text-muted">JPG, PNG, WEBP — max 3MB each</small>
        </div>
    </form>

    <!-- Existing Images Grid -->
    <div id="galleryGrid_{{ $entityType }}" class="row mt-4">
        <div class="col-12 text-center text-muted" id="galleryLoading_{{ $entityType }}">
            <i class="fa fa-spinner fa-spin"></i> Loading images...
        </div>
    </div>
</div>

<!-- Dropzone JS -->
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
Dropzone.autoDiscover = false;

(function() {
    var entityId   = {{ $entityId }};
    var entityType = '{{ $entityType }}';
    var uploadUrl  = '{{ $uploadRoute }}';
    var deleteUrl  = '{{ $deleteRoute }}';
    var imagesUrl  = '{{ $imagesRoute }}';
    var csrfToken  = '{{ csrf_token() }}';


    function loadGallery() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', imagesUrl + '?id=' + entityId + '&type=' + entityType, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var res = JSON.parse(xhr.responseText);
                var grid = document.getElementById('galleryGrid_' + entityType);
                grid.innerHTML = '';

                if (!res.images || res.images.length === 0) {
                    grid.innerHTML = '<div class="col-12 text-center text-muted">No images uploaded yet.</div>';
                    return;
                }

                res.images.forEach(function(img) {
                    grid.innerHTML += galleryCard(img.id, img.url);
                });
            }
        };
        xhr.send();
    }

    function galleryCard(id, url) {
        return '<div class="col-md-3 col-sm-4 col-6 mb-3" id="galleryItem_' + id + '">' +
            '<div class="position-relative">' +
                '<img src="' + url + '" class="img-fluid rounded shadow-sm" style="height:140px;width:100%;object-fit:cover;">' +
                '<button type="button" onclick="deleteGalleryImage(' + id + ', \'' + entityType + '\')" ' +
                    'class="btn btn-danger btn-sm position-absolute" ' +
                    'style="top:5px;right:5px;padding:2px 7px;border-radius:50%;">' +
                    '<i class="fa fa-times"></i>' +
                '</button>' +
            '</div>' +
        '</div>';
    }

    window.deleteGalleryImage = function(id, type) {
        var modal = document.createElement('div');
        modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;';
        modal.innerHTML = `
            <div style="background:#fff;border-radius:12px;padding:30px;max-width:360px;width:90%;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                <h5 style="margin-bottom:8px;font-weight:700;color:#333;">Delete Image?</h5>
                <p style="color:#888;font-size:14px;margin-bottom:20px;">This action cannot be undone.</p>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button id="cancelDeleteBtn" style="flex:1;padding:10px;border:1px solid #ddd;border-radius:8px;background:#f8f9fa;color:#555;font-weight:600;cursor:pointer;">Cancel</button>
                    <button id="confirmDeleteBtn" style="flex:1;padding:10px;border:none;border-radius:8px;background:#dc3545;color:#fff;font-weight:600;cursor:pointer;">Delete</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        document.getElementById('cancelDeleteBtn').onclick = function() {
            document.body.removeChild(modal);
        };

        document.getElementById('confirmDeleteBtn').onclick = function() {
            document.body.removeChild(modal);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', deleteUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.onload = function() {
                var res = JSON.parse(xhr.responseText);
                if (res.status === 200) {
                    var item = document.getElementById('galleryItem_' + id);
                    if (item) item.remove();
                    var grid = document.getElementById('galleryGrid_' + type);
                    if (grid && grid.children.length === 0) {
                        grid.innerHTML = '<div class="col-12 text-center text-muted">No images uploaded yet.</div>';
                    }
                }
            };
            xhr.send('id=' + id + '&type=' + type + '&_token=' + csrfToken);
        };
    };

    // Init Dropzone
    var dz = new Dropzone('#galleryDropzone_' + entityType, {
        url: uploadUrl,
        paramName: 'image',
        maxFilesize: 3,
        acceptedFiles: 'image/jpeg,image/png,image/webp',
        addRemoveLinks: false,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        params: { type: entityType, id: entityId },
        success: function(file, res) {
            if (res.status === 200) {
                var grid = document.getElementById('galleryGrid_' + entityType);
                var noImg = grid.querySelector('.text-muted');
                if (noImg) noImg.parentElement.remove();
                grid.innerHTML += galleryCard(res.id, res.url);
                this.removeFile(file);
            }
        },
        error: function(file, msg) {
            alert(typeof msg === 'string' ? msg : 'Upload failed.');
            this.removeFile(file);
        }
    });

    // Tab click pe load
    document.querySelectorAll('a[href="#basictab5"], a[href="#basictab3"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function() { loadGallery(); });
        tab.addEventListener('click', function() {
            setTimeout(function() { loadGallery(); }, 100);
        });
    });

    // Page load pe hamesha load karo
    window.addEventListener('load', function() {
        loadGallery();
    });

})();
</script>

<style>
.dropzone .dz-message { text-align: center; padding: 30px 0; }
.dropzone:hover { background: #eef9fc !important; }
</style>
