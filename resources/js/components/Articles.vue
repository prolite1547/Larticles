<template>
    <div>
        <h2 id="title">Articles</h2>
        <!-- <form  @submit.prevent="addArticle" class="mb-4"> -->
           <div class="form mb-4">
                <div  class="form-group">
                <input type="text" class="form-control" placeholder="Title" v-model="article.title">
                </div>
                <div class="form-group">
                    <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea>
                </div>
                <button @click="addArticle" class="btn btn-primary">Save</button>
                <button @click="clearFields" class="btn btn-default">Cancel</button>
           </div>
        <!-- </form> -->

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled:!pagination.prev_page_url}]" class="page-item"><a @click="fetchArticles(pagination.prev_page_url)" class="page-link">Previous</a></li>
                 <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li> 
                <li v-bind:class="[{disabled:!pagination.next_page_url}]" class="page-item"><a @click="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="article in articles" v-bind:key="article.id">
            <h3>{{ article.title }}</h3>
            <p>{{ article.body }}</p>
            <hr>
            <a @click="editArticle(article)" href="#title" class="btn btn-warning mb-2">Edit</a>
            <button @click="deleteArticle(article.id)" class="btn btn-danger">Delete</button>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled:!pagination.prev_page_url}]" class="page-item"><a @click="fetchArticles(pagination.prev_page_url)" class="page-link">Previous</a></li>
                 <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li> 
                <li v-bind:class="[{disabled:!pagination.next_page_url}]" class="page-item"><a @click="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</template>

<script>
   export default{
        data(){
            return{
                articles:[],
                article: {
                    id: '',
                    title: '',
                    body: ''
                },
                article_id: '',
                pagination: {},
                edit: false
            }
        },
        created(){
            this.fetchArticles();
        },
        methods: {
            fetchArticles(page_url){
                let vm = this;
                page_url = page_url || 'api/articles';
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                     this.articles = res.data;
                     vm.createPagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
            },
            createPagination(meta, links){
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url : links.next,
                    prev_page_url : links.prev
                }
                this.pagination = pagination;
            },
            deleteArticle(id){
               if(confirm("Are you sure?")){
                   let url = 'api/article/' + id;
                    fetch(url, {
                       method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Article Removed');
                        this.fetchArticles();
                    })
                    .catch(err=>console.log(err));

               }
            },
            addArticle(){
                if(this.edit==false){
                    //Add
                    fetch('api/article',{
                        method: "post",
                        body: JSON.stringify(this.article),
                        headers:{
                            'content-type':'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.article.title='';
                        this.article.body='';
                        alert("New article has been added");
                        this.fetchArticles();
                    })
                    .catch(err=>console.log(err));

                }else{
                    //Update
                     fetch('api/article',{
                        method: "put",
                        body: JSON.stringify(this.article),
                        headers:{
                            'content-type':'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.article.title='';
                        this.article.body='';
                        alert("Article has been updated");
                        this.fetchArticles();
                    })
                    .catch(err=>console.log(err));

                }
            },
            editArticle(article){
                this.edit = true;
                this.article.id = article.id;
                this.article.article_id = article.id;
                this.article.title = article.title;
                this.article.body = article.body;
            },
            clearFields(){
                this.edit = false;
                this.article.id = '';
                this.article.article_id = '';
                this.article.title = '';
                this.article.body = '';

            }
        }
   }
</script>