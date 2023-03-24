import './bootstrap';

import Alpine from 'alpinejs';
import swal from 'sweetalert2';

// If you are using JavaScript/ECMAScript modules:
import Dropzone from "dropzone";
// If you are using an older version than Dropzone 6.0.0,
// then you need to disabled the autoDiscover behaviour here:
Dropzone.autoDiscover = false;

import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import pt_ES from 'filepond/locale/es-es.js';
import 'sweetalert2/dist/sweetalert2.min.css';

import 'dropzone/dist/basic.css';
// import 'dropzone/dist/dropzone.css';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

window.Alpine = Alpine;
window.Swal = swal;
window.FilePond = FilePond;
window.FilePondPluginImagePreview = FilePondPluginImagePreview;

Alpine.start();
FilePond.setOptions(pt_ES);

// Sweetalert2 - Eliminar registros
window.deleteConfirm = function (formId) {
  swal.fire({
    title: 'Esta seguro de eliminar el registro?',
    text: "Este registro se eliminará definitivamente!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, eliminar!',
    cancelButtonText: 'No, cancelar!',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      swal.fire(
        'Eliminado!',
        'El registro fue eliminado.',
        'success'
      )
      document.getElementById(formId).submit();
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      swal.fire({
        icon:  'error',
        title: 'Cancelado.',
        text:  'El registro no fue eliminado.!',
        timer: 2000,
        showConfirmButton: false
      });
    }
  });
}

// Dropzone
let dropzone = new Dropzone("#dropzonenpm", {
  paramName: 'file',
  acceptedFiles: "image/*",
  addRemoveLinks: true,
  maxFiles: 1,
  uploadMultiple: false,
  dictDefaultMessage: "Suelte los archivos aquí o haga clic para cargar el documento",
  dictRemoveFile: "Quitar imagen",
  headers: {
    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
  },

  init: function () {
    if (document.querySelector('[name="image"]').value.trim()) {
      let imagePublished = {};
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
/* dropzone.on("complete", function(file) {
  dropzone.removeFile(file);
}); */