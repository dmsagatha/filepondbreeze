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


Dropzone.options.imagesDropzone = {
  paramName: "file",
  dictDefaultMessage: "Suelte los archivos aquí o haga clic para cargar la imagen123",
  acceptedFiles: 'image/*',
  addRemoveLinks: true,
  dictRemoveFile: "Quitar archivo",
  maxFiles: 1,
  uploadMultiple: false,

  success: function (file, response) {
    document.querySelector('[name="image"]').value = response.image;
  },

  removedfile: function (file, response) {
    file.previewElement.parentNode.removeChild(file.previewElement);
    document.querySelector('[name="image"]').value = "";
  },


  maxfilesexceeded: function (file) {
    this.removeAllFiles();
    this.addFile(file);
  },
};

// let my_dropzone = Dropzone("#images_dropzone");

// , {
//   paramName: "file",
//   dictDefaultMessage: "Suelte los archivos aquí o haga clic para cargar la imagen",
//   acceptedFiles: 'image/*',
//   addRemoveLinks: true,
//   dictRemoveFile: "Quitar archivo",
//   maxFiles: 1,
//   uploadMultiple: false,

//   success: function (file, response) {
//     document.querySelector('[name="image"]').value = response.image;
//   },

//   removedfile: function (file, response) {
//     file.previewElement.parentNode.removeChild(file.previewElement);
//     document.querySelector('[name="image"]').value = "";
//   },


//   maxfilesexceeded: function (file) {
//     this.removeAllFiles();
//     this.addFile(file);
//   },

//   init: function () {
//     this.on("maxfilesexceeded", function (file) {
//       this.removeAllFiles();
//       this.addFile(file);
//     })

//     if (document.querySelector('[name="image"]').value.trim()) { // si hay algo
//       let imagePublished = {}
//       imagePublished.size = 1234;
//       imagePublished.name = document.querySelector('[name="image"]').value;

//       this.options.addedfile.call(this, imagePublished);
//       this.options.thumbnail.call(this, imagePublished, "/uploads/" + imagePublished.name);
//       imagePublished.previewElement.classList.add("dz-success", "dz-complete");
//     }

//   }
// });