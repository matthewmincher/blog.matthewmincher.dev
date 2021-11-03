import 'autogrow-textarea';
import hljs from "highlight.js";
import 'highlight.js/styles/default.css';


document.addEventListener('DOMContentLoaded', evt => {
    document.querySelectorAll('pre code').forEach(el => {
        hljs.highlightElement(el);
    })
})
