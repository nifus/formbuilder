<input type="hidden" name="form[preview_image]" value="">

<span style="display:none" id="previewImageUploadContainer">
    <img id="previewImageUpload">
    <br style="clear:both">
    </span>

<div>
    <input type="button" value="Загрузить фото" id="previewImageUploadButton">
    <input type="button" style="display:none" value="Прервать" id="previewImageUploadButtonStop">

    <img src="/packages/nifus/formbuilder/SimpleImageUpload/img/wait.gif" id="previewImageUploadWait"
         style="display:none;width:25px;vertical-align: middle;">
</div>
<img
    onload="imgUpload.create('{{ route('fb.action',['ext'=>'SimpleImageUpload','action'=>'upload'] )}}',{'countFiles':12,'returnName':'images','image':'previewImageUploadImage','file':'previewImageUploadFile','form':'previewImageUploadForm', 'button':'previewImageUploadButton','wait':'previewImageUploadWait', 'container':'previewImageUploadContainer','dir':'news/images','exts':'', 'width':246,'height':147, 'size':16000000 },{{$result}},'{{ route('fb.action',['ext'=>'SimpleImageUpload','action'=>'delete'] )}}')"
    style="display:none" src="/packages/nifus/formbuilder/SimpleImageUpload/img/close.gif">