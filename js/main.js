$(document).ready(function(){
    loadNews();

    function loadNews(){
        $.post("includes/loadNews.inc.php", function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                let newsHTML = '';
                data.forEach(article => {
                    newsHTML += `
                        <li>
                            <a href="${article.link} target="_blank"">${article.title}</a>
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
});