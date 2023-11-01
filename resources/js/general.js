function ToggleBtnLoader(btnLoader) {

    let spinner = "<span class='spinner-border spinner-border-sm'></span> Processing...";

    if (!btnLoader.is(":disabled")) {
        btnLoader.attr("data-old-text", btnLoader.text());
        btnLoader
            .html(spinner)
            .prop("disabled", true);
    }else{
        let oldText = btnLoader.attr("data-old-text")
        btnLoader.html(oldText).prop("disabled", false);
    }

}
