let timer;

$(document).ready(function() {
    $(".result").on("click auxclick", function(e) {
        let id = $(this).attr("data-linkId");
        let url = $(this).attr("href");

        // if(!id) alert("data-linkId attribute not found...");

        increaseLinkClicks(id, url, e.type);

        return false;
    });

    let grid = $(".imageResults");

    grid.on("layoutComplete", function() {
        $(".gridItem img").css("visibility", "visible");
    });

    grid.masonry({
        itemSelector: ".gridItem",
        columnWidth: 200,
        gutter: 5,
        isInitLayout: false
    });
});

function loadImage(src, className) {
    var image = $("<img>");

    image.on("load", function() {
        $("." + className + " a").append(image);

        clearTimeout(timer);
        timer = setTimeout(function() {
            $(".imageResults").masonry();
        }, 500);
    });

    image.on("error", function() {
        
    });

    image.attr("src", src);
}

function increaseLinkClicks(linkId, url, type) {
    $.post("ajax/updateLinkCount.php", {linkId: linkId})
    .done(function(result) {
        if(result != "") {
            alert(result);
            return;
        }

        if(type == "click") window.location.href = url;
    });
}
