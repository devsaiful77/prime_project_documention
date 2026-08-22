<?php
/**
 * User:Muajjam Hossain
 * Email:muajjam.imu@gmail.com
 * Created by Muajjam Hossain<muajjam.imu@gmail.com> on 518/8/2024.
 */
?>
@php
    $item=$attachment_item;    
@endphp
@if($item!=0)
    @for($i = 0; $i < $item; $i++)
        <img id="imgInDynamic{{$i}}" alt="imgInDynamic" width="100" height="100" style="display:none;" />
        <a id="downloadBtn{{$i}}" href="#" style="display:none;" download>Download File</a>
        <input type="file" name="file_name[]" class="form-control" 
            onchange="
            var file = this.files[0];
            var img = document.getElementById('imgInDynamic{{$i}}');
            var downloadBtn = document.getElementById('downloadBtn{{$i}}');
            if (file) {
                if (file.type.startsWith('image/')) {
                    // Show image preview
                    img.style.display = 'block';
                    img.src = window.URL.createObjectURL(file);
                    downloadBtn.style.display = 'none';
                    downloadBtn.href = '';
                } else {
                    // Hide image and show download button
                    img.style.display = 'none';
                    img.src = '';
                    downloadBtn.style.display = 'block';
                    downloadBtn.href = window.URL.createObjectURL(file);
                    downloadBtn.innerHTML = 'Download ' + file.name;
                }
            } else {
                // Hide both if no file is selected
                img.style.display = 'none';
                img.src = '';
                downloadBtn.style.display = 'none';
                downloadBtn.href = '';
            }">
    @endfor

@elseif($type == 'complaint')
    <img id="imgInComplain" alt="complaint image" width="100" height="100" style="display:none;" />
    <a id="downloadBtnComplain" href="#" style="display:none;" download>Download File</a>
    <input type="file" name="file_name[]" multiple class="form-control" 
        onchange="
        var file = this.files[0];
        var img = document.getElementById('imgInComplain');
        var downloadBtn = document.getElementById('downloadBtnComplain');
        if (file) {
            if (file.type.startsWith('image/')) {
                // Show image preview
                img.style.display = 'block';
                img.src = window.URL.createObjectURL(file);
                downloadBtn.style.display = 'none';
                downloadBtn.href = '';
            } else {
                // Hide image and show download button
                img.style.display = 'none';
                img.src = '';
                downloadBtn.style.display = 'block';
                downloadBtn.href = window.URL.createObjectURL(file);
                downloadBtn.innerHTML = 'Download ' + file.name;
            }
        } else {
            // Hide both if no file is selected
            img.style.display = 'none';
            img.src = '';
            downloadBtn.style.display = 'none';
            downloadBtn.href = '';
        }">

@else
    <img id="imgInWform" alt="W-Form image" width="100" height="100" style="display:none;" />
    <a id="downloadBtnWform" href="#" style="display:none;" download>Download File</a>
    <input type="file" name="file_name[]" class="form-control" 
        onchange="
        var file = this.files[0];
        var img = document.getElementById('imgInWform');
        var downloadBtn = document.getElementById('downloadBtnWform');
        if (file) {
            if (file.type.startsWith('image/')) {
                // Show image preview
                img.style.display = 'block';
                img.src = window.URL.createObjectURL(file);
                downloadBtn.style.display = 'none';
                downloadBtn.href = '';
            } else {
                // Hide image and show download button
                img.style.display = 'none';
                img.src = '';
                downloadBtn.style.display = 'block';
                downloadBtn.href = window.URL.createObjectURL(file);
                downloadBtn.innerHTML = 'Download ' + file.name;
            }
        } else {
            // Hide both if no file is selected
            img.style.display = 'none';
            img.src = '';
            downloadBtn.style.display = 'none';
            downloadBtn.href = '';
        }">

@endif