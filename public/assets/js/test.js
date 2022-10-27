// window.onload=()=>{
//     const FiltersForm = document.querySelector('#test12');
//     document.querySelectorAll('#test12 input').forEach(input=>{
//         input.addEventListener("change",()=>{
//             const Form = new FormData(FiltersForm);
//             console.log(Form);
//         })
//     })
// }
// window.onload=()=> {
//     const FiltersForm = document.querySelector('#addToCart');
//         document.querySelectorAll('#form2').forEach(value=>{
//         input.addEventListener("change",()=>{
//             // const Form = new FormData(FiltersForm);
//             console.log("ok");
//         })
//     });


// }
document.ready(function() {
    $('#form2').click(function() {
        $.ajax({
            url: "{{ path('add_add_cart') }}/" + $(this).data('id'),
            type: "GET",
            success: function(response) {
                // Change #total text
                $('#total').text(response.total);
            }
        });
    });
});
