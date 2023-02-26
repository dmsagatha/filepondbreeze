import './bootstrap';

import Alpine from 'alpinejs';
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import pt_ES from 'filepond/locale/es-es.js';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

window.Alpine = Alpine;
window.FilePond = FilePond;
window.FilePondPluginImagePreview = FilePondPluginImagePreview;

Alpine.start();

FilePond.setOptions(pt_ES);