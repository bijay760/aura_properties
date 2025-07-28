// $(document).ready(function () {
//     $(document).on("click", '[data-toggle="lightbox"]', function (event) {
//         event.preventDefault();
//         $(this).ekkoLightbox();
//     });
// });

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(window).on("load", function () {
    // Animate loader off screen
    $(".se-pre-con").fadeOut("slow");
});
