import './bootstrap';
import Dropzone from "dropzone";

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

// Deshabilitar el comportamiento de detección automática
Dropzone.autoDiscover = false;

let dropzone = new Dropzone("#dropzone", {
  paramName: "file",
  acceptedFiles: 'image/*',
  addRemoveLinks: true,
  maxFiles: 1,
  uploadMultiple: false,
  dictDefaultMessage: "Suelte los archivos aquí o haga clic para cargar la imagen",
  dictRemoveFile: "Quitar archivo",

  init: function () {
    if (document.querySelector('[name="image"]').value.trim()) { // si hay algo
      let imagePublished = {}
      imagePublished.size = 1234;
      imagePublished.name = document.querySelector('[name="image"]').value;

      this.options.addedfile.call(this, imagePublished);
      this.options.thumbnail.call(this, imagePublished, "/uploads/" + imagePublished.name);
      imagePublished.previewElement.classList.add("dz-success", "dz-complete");
    }
  }
});

dropzone.on('success', function (file, response) {
  document.querySelector('[name="image"]').value = response.image;
});

dropzone.on('removedfile', function (file) {
  document.querySelector('[name="image"]').value = "";
});