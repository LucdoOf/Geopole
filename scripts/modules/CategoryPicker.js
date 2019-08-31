class CategoryPicker {

    constructor() {
        this.header = $("header");
        this.ctn = $("header #menu");
        this.artcLink = $("#artc-link");
        this.catCtn = this.ctn.find("#menu-categories");
        this.categories = this.catCtn.find(".menu-category");
        this.artcCtn = this.ctn.find("#menu-artc");
        this.showed = false;
        this._bind();
        if(this.categories.length > 0){
            this.selectCategory($(this.categories.get(0)));
        }
    }

    _bind(){
        this.artcLink.on({
            click: e => {
                e.preventDefault();
                e.stopPropagation();
                if(this.showed){
                    this.hide();
                } else {
                    this.show();
                }
            }
        });
        this.categories.each((index,cat) => {
           $(cat).on({
               mouseenter: () => {
                   this.selectCategory($(cat));
               }
           });
        });
    }

    selectCategory(el){
        const catId = el.data("id");
        this.categories.each((index,cat) => {
           $(cat).removeClass("selected");
        });
        el.addClass("selected");
        this.displayArticles(catId);
    }

    displayArticles(id){
        $.ajax({
           url:"/display_home",
           method:"POST",
           action:"POST",
           data:{"type":"category-articles", "id":id}
        }).done(data => {
            this.artcCtn.html(data);
        });
    }

    show(){
        this.header.addClass("menu-expanded articles-expanded");
        this.showed = true;
    }

    hide(){
        this.header.removeClass("menu-expanded articles-expanded");
        this.showed = false;
    }


}