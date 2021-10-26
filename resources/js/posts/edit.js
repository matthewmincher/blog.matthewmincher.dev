import Tagify from '@yaireo/tagify';

document.addEventListener('DOMContentLoaded', evt => {
    var tagify = new Tagify(document.getElementById('tagInput'), {
        whitelist: window.tagWhitelist,
        dropdown: {
            maxItems: 20,
            classname: "tags-look",
            enabled: 0,
            closeOnSelect: false
        }
    });
})



