import Tagify from '@yaireo/tagify';
import SimpleMDE from 'simplemde';
import 'simplemde/dist/simplemde.min.css';


document.addEventListener('DOMContentLoaded', evt => {
    const tagify = new Tagify(document.getElementById('tagInput'), {
        whitelist: window.tagWhitelist,
        dropdown: {
            maxItems: 20,
            classname: "tags-look",
            enabled: 0,
            closeOnSelect: false
        }
    });

    const simpleMDE = new SimpleMDE({
        toolbar: ['bold', 'italic', 'strikethrough', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'side-by-side', 'fullscreen', '|', 'guide']
    });
})



