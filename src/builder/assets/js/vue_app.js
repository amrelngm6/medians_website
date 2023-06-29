var rootURL = 'http://192.168.1.99/anthony/phase3/';
// var rootURL = 'https://www.beatkongs.com/builder1/';
var imageURL = 'https://beatkongz.s3.amazonaws.com/';
var userID = userID ? userID : 1;


var MainApp = new Vue({
    el: '#tracks-player',
    data: {
        cartData: {},
        hashID: '1',
        userID: '1'
    },
    created() {
        let t = this;
        this.userID = userID;
        setTimeout(function(){
            t.fetchSession().then(e => (t.fetchCart()));
        }, 10);   
    },
    
    methods: 
    {
        async fetchSession() 
        {
            let t  = this;
            const params = new URLSearchParams([]);
            params.append('filter_type', 'fetchSession');
            params.append('hash_id',  this.hashID);
            params.append('userid', t.userID);
            params.append('title', PlayerAPP.searchTitle);
            
            if (params)
            {
                await axios.post(rootURL + 'endpoints/a_api?', params.toString() ).then(response => 
                {
                    let Result = response.data;
                    if (response.data.new_session)
                    {
                        t.hashID = response.data.new_session;
                        t.fetchSession();
                    } else {
                        if (PlayerAPP)
                        {
                            PlayerAPP.playlist = JSON.parse(Result.sql);
                            CartApp.userData = JSON.parse(Result.sql).userData;
                        }
                    }
                });
            }    
        },
        async fetchCart() 
        {
            let t  = this;
            const params = new URLSearchParams([]);
            params.append('filter_type', 'fetchCart');
            params.append('userid', t.userID);
            params.append('hash_id',  this.hashID);
            
            if (params)
            {
                await axios.post(rootURL + 'endpoints/a_api?', params.toString() ).then(response => 
                {
                    let Result = response.data;
                    if (PlayerAPP)
                    {
                        PlayerAPP.cart = JSON.parse(Result.sql);
                        CartApp.cartData = JSON.parse(Result.sql);
                    }
                });
            }    
        },
        async addCart() 
        {
            let t  = this;
            const params = new URLSearchParams([]);
            params.append('filter_type', 'addCart');
            params.append('id', t.cartData.id);
            params.append('purchaseType', t.cartData.type);
            params.append('price', t.cartData.price);
            params.append('hash_id',  this.hashID);
            
            if (params)
            {
                await axios.post(rootURL + 'endpoints/a_api?', params.toString() ).then(response => 
                {
                    let Result = response.data;
                    if (Result.status && !Result.message)
                    {
                        alertify.success('Added to cart');
                    } else {
                        alertify.error(Result.message);
                    }
                });
            }    
        },
        fetchStats() 
        {
            let t  = this;
            axios.post(rootURL).then(response => 
            {
                if (response.data)
                {

                }
            });
        }
    }
})

