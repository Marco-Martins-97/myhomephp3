$(document).ready(function(){
    loadNews();
    loadModels();

    function loadNews(){
        $.post("includes/loadNews.inc.php", function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                let newsHTML = '';
                data.forEach(article => {
                    newsHTML += `
                        <li data-id="${article.newId}">
                            <a href="${article.link}" target="_blank">${article.title}</a>
                            <p>${article.content}</p>
                        </li>
                    `;
                });
                $('#news-feed').html(newsHTML);
            } else {
                console.log("Error: " + status);
            }
        });
    }

    function loadNew(newId){
        $.post("includes/loadNews.inc.php", { newId }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                $('#new-display').html(`
                    <div id="new">
                        <h2><a href="${data.link}" target="_blank">${data.title}</a></h2>
                        <p>${data.content}</p>
                        <p class="author">${data.author}</p>
                    </div>
                `);
            } else {
                console.log("Error: " + status);w            }
        });
    }

    function loadModels(){
        const imgDir = "img/uploads/";
        $.post("includes/loadModels.inc.php", function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                let modelHTML = '';
                data.forEach(model => {
                    modelHTML += `
                        <li class="card">
                            <img src="${imgDir + model.imgName}" alt="${model.modelName}">
                            <div class="description">
                                <h3>${model.modelName}</h3>
                                <p>Área: ${model.area} m²</p>
                                <p>Quartos: ${model.bedrooms}</p>
                                <p>Wc: ${model.bathrooms}</p>
                            </div>
                        </li>
                    `;
                });
                $('.gallery').html(modelHTML);
            } else {
                console.log("Error: " + status);
            }
        });
    }

    $(document).on('click', '#news-feed li', function() {
        const newId = $(this).data('id');
        loadNew(newId);
        const offset = 3 * parseFloat(getComputedStyle(document.documentElement).fontSize);
        $('html, body').animate({ scrollTop: $('#new').offset().top-offset }, 'slow');
    });

});