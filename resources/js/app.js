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

Dropzone.autoDiscover = false;

const dropzone = new Dropzone("#dropzone", {
  dictDefaultMessage: "Suelte los archivos aquí o haga clic para cargar la imagen",
  acceptedFiles: 'image/*',
  addRemoveLinks: true,
  dictRemoveFile: "Quitar archivo",
  maxFiles: 1,
  uploadMultiple: false,

  init: function () {
    if (document.querySelector('[name="imagen"]').value.trim()) { // si hay algo
      const imagePublished = {}
      imagePublished.size = 1234;
      imagePublished.name = document.querySelector('[name="imagen"]').value;
      
      this.options.addedfile.call(this, imagePublished);
      this.options.thumbnail.call(this, imagePublished, "/uploads/" + imagePublished.name);
      imagePublished.previewElement.classList.add("dz-success", "dz-complete");
    }
  }
});

dropzone.on('success', function (file, response) {
  document.querySelector('[name="imagen"]').value = response.imagen;
});

dropzone.on('removedfile', function () {
  document.querySelector('[name="imagen"]').value = "";
});