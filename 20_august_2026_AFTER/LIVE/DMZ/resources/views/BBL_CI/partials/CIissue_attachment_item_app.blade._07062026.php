
@php
    $item = $attachment_item;
@endphp
@if(!empty($item))
    @foreach($item as $key => $row)
        <div class="input-wrapper mb-3">
            @if(isset($send_back))
                <label for="" class="input-label">{{ $row->name }}</label>
            @else
                <label for="" class="input-label">{{ $row->name }}@if( $row->is_required == 1 ) <span style="color: red">*</span> @endif</label>
            @endif
            <input type="file" name="file_name[{{$key}}][file]" class="form-control file-upload">
            <input type="hidden" name="file_name[{{$key}}][name]" value="{{ $row->name }}">
            <input type="hidden" name="file_name[{{$key}}][is_required]" value="{{ isset($send_back) ? '' : $row->is_required}}">
            <div class="error-message file_name_{{$key}}_file"></div>
        </div>
    @endforeach
@endif

<script src="{{ URL::asset('public/BBL_CI/js/image-compression.js') }}"></script>
<script nonce="{{ app('csp_nonce') }}">
    $(document).ready(function(){
        let processingFile = false;

        async function compressImageFile(file) {
            const options = {
                maxWidthOrHeight: 1920,
                useWebWorker: false,
                initialQuality: 0.8
            };

            const compressedFile = await imageCompression(file, options);
            const base64 = await imageCompression.getDataUrlFromFile(compressedFile);

            return {
                base64: base64,
                size: compressedFile.size,
                file: compressedFile
            };
        }

        $(document).off('change', '.file-upload').on('change', '.file-upload', async function(){
            if (processingFile) return;
            processingFile = true;

            const $input = $(this);
            var file = $input[0].files[0];

            if (!file) {
                processingFile = false;
                return;
            }

            $input.next('.file-error').remove();

            var allowedTypes = [
                'image/jpeg', 'image/png', 'image/jpg',
                'application/pdf',
                'image/heif', 'image/heic',
                'image/heif-sequence', 'image/heic-sequence'
            ];

            var allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'heif', 'heic'];
            var maxSize = 3 * 1024 * 1024;
            var ext = file.name.split('.').pop().toLowerCase();

            // ---- Type validation ----
            if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(ext)) {
                var $error = $('<span class="file-error text-danger">Only jpeg, png, jpg, pdf, heif, heic files are allowed.</span>').insertAfter($input);
                $input.val("");
                setTimeout(function(){ $error.fadeOut(300, function(){ $(this).remove(); }); }, 5000);
                processingFile = false;
                return;
            }

            // ---- Size validation ----
            if (file.size > maxSize) {
                var $error = $('<span class="file-error text-danger">Maximum allowed file size is 3MB.</span>').insertAfter($input);
                $input.val("");
                setTimeout(function(){ $error.fadeOut(300, function(){ $(this).remove(); }); }, 5000);
                processingFile = false;
                return;
            }

            var needsCompression = file.type !== "application/pdf" && ext !== "heic" && ext !== "heif";

            if (needsCompression) {
                $('.loadingOverlay').removeClass('loader-none'); // show loader

                try {
                    const { base64, size, file: compressedBlob } = await compressImageFile(file);

                    const compressedFile = new File([compressedBlob], file.name, { type: compressedBlob.type });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    $input[0].files = dataTransfer.files;

                    console.log('base64:', base64);
                    console.log('size (bytes):', size);
                    console.log('size (KB):', (size / 1024).toFixed(2) + ' KB');
                    console.log('file name:', compressedFile.name);
                    console.log('file type:', compressedFile.type);
                } catch (err) {
                    console.error('Compression failed:', err);
                    $('<span class="file-error text-danger">File processing failed. Please try again.</span>').insertAfter($input);
                    $input.val("");
                } finally {
                    $('.loadingOverlay').addClass('loader-none');
                    processingFile = false;
                }
            } else {
                processingFile = false;
            }
        });
    });
</script>