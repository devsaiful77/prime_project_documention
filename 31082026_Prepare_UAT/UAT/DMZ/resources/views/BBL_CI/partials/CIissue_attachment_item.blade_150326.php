
@php
    $item = $attachment_item;
@endphp
@if(!empty($item))
    @foreach($item as $key => $row)
        <div class="card card-color">
            <div class="card-body">
                <div class="form-group">
                    @if(isset($send_back))
                        <label class="mb-1">{{ $row->name }} </label>
                    @else
                        <label class="mb-1">{{ $row->name }}  @if($row->is_required == 1) <span style="color: red">*</span> @endif </label>
                    @endif
                    <input type="file" name="file_name[{{$key}}][file]" class="form-control file-upload">
                    <input type="hidden" name="file_name[{{$key}}][name]" value="{{ $row->name }}">
                    <input type="hidden" name="file_name[{{$key}}][is_required]" value="{{ isset($send_back) ? '' : $row->is_required}}">
                    <div class="error-message file_name_{{$key}}_file"></div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script src="{{ URL::asset('public/BBL_CI/js/image-compression.js') }}"></script>
    

    <script nonce="{{ app('csp_nonce') }}">

    $(document).ready(function(){

        async function compressImageFile(file) {

            let workingFile = file;
            let mimeType = file.type || 'image/jpeg';

            // ---- Compression ----
            const options = {
                maxWidthOrHeight: 1920,
                useWebWorker: false,
                initialQuality: 0.8
            };

            const compressedFile = await imageCompression(workingFile, options);

            const base64 = await imageCompression.getDataUrlFromFile(compressedFile);

            return {
                base64: base64,
                size: compressedFile.size,
                file: compressedFile
            };
        }


        $(document).on('change', 'input[type="file"]', async function(){
            //$('.loadingOverlay').removeClass('loader-none');

            var file = $(this)[0].files[0];
            if(!file) return;

            $(this).next('.file-error').remove();

            var allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'application/pdf',
                'image/heif',
                'image/heic',
                'image/heif-sequence',
                'image/heic-sequence'
            ];

            var allowedExtensions = ['jpg','jpeg','png','pdf','heif','heic'];

            var maxSize = 3 * 1024 * 1024;

            var ext = file.name.split('.').pop().toLowerCase();

            // ---- Type validation ----
            if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(ext)) {
		//$('.loadingOverlay').addClass('loader-none');
                var $error = $('<span class="file-error text-danger">Only jpeg, png, jpg, pdf, heif, heic files are allowed.</span>').insertAfter($(this));
                $(this).val("");

                setTimeout(function(){
                    $error.fadeOut(300, function(){ $(this).remove(); });
                },5000);
                
                return;
            }

            // ---- Size validation ----
            if (file.size > maxSize) {

                var $error = $('<span class="file-error text-danger">Maximum allowed file size is 3MB.</span>').insertAfter($(this));
                $(this).val("");

                setTimeout(function(){
                    $error.fadeOut(300, function(){ $(this).remove(); });
                },5000);
                //$('.loadingOverlay').addClass('loader-none');
                return;
            }

            // ---- Image compression (skip PDF) ----
            // ---- Image compression (skip PDF & HEIC/HEIF) ----
            if(file.type !== "application/pdf" && ext !== "heic" && ext !== "heif") {
                const { base64, size, file: compressedBlob } = await compressImageFile(file);

                // Convert blob to File
                const compressedFile = new File([compressedBlob], file.name, { type: compressedBlob.type });

                // Replace input file with compressed version
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                $(this)[0].files = dataTransfer.files;

                
                console.log('base64:', base64);
                console.log('size (bytes):', size);
                console.log('size (KB):', (size / 1024).toFixed(2) + ' KB');
                console.log('file name:', compressedFile.name);
                console.log('file type:', compressedFile.type);
            }

            //$('.loadingOverlay').addClass('loader-none');

        });

    });
</script>
