const deleted = (component, method, elementId) => {
    Swal.fire({
        title: "Estas seguro?",
        text: "¡No puedes revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatchTo(component,method,{ id: elementId})
        }
    });
};

const  alertSuccess  = (message) => {
    Swal.fire({
        title: "Éxito!",
        text: message,
        icon: "success"
      });
};


const alertFailed = (message) => {
    Swal.fire({
        icon: 'error',
        title: message,
        showConfirmButton: true,
     })
} 