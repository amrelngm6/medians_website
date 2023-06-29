


    // App
    // =======================
    var PlayerAPP = new Vue({
        el: '.app2',
        data: function() {
            return {
                cart: {cost:0},
                searchTitle: '',
                currentID: 0,
                currentDuration: 0,
                currentDurationText: '00',
                rangeSlider: 0,
                element: 0,
                timerplayed: 0,
                player: {
                    currentTrack: 0,
                    elapsed: 0,
                    playing: false,
                    repeat: false,
                    shuffle: true,
                    volume: 0
                },
                playlist: {
                    tracks: []
                }
            }
        },
        created()
        {
            this.element = document.getElementById("main-audio");
            this.timerplayed = this.fmtMSS(0);
            this.player.volume = 1;
            this.setVolume();

        },
        computed: {
            currentTrack: function() {
                return this.playlist.tracks[this.player.currentTrack];
            },
            playlistDuration: function() {
                var duration = 0,
                    tracks = this.playlist.tracks,
                    i;
                
                for (i = 0; i < tracks.length; i += 1) {
                    duration += tracks[i].duration;
                }
                
                return duration;
            }
        },
        watch: {
            searchTitle(data)
            {
                if (MainApp)
                {
                    MainApp.fetchSession();
                }
            },
            rangeSlider(range) 
            {
                
                // this.element = document.getElementById("main-audio");
                if (timer)
                {
                    clearInterval(timer);
                } else {
                    var _this = this,
                        timer = setInterval(function() {
                            if (_this.element.played.length > 0)
                            {
                                _this.updateTimer();
                                _this.rangeSlider = _this.element.currentTime;
                                // return 0;
                            }
                        }, 100);
                }

                if (parseInt(range + 2) - parseInt(this.element.currentTime) > 2)
                {
                    this.element.currentTime = range;
                }

            }
        },
        methods: {
            getCart: function()
            {
                MainApp.fetchCart().then(element=>console.log(this.cart));
            },
            getCartModal: function()
            {
                MainApp.fetchCart().then(element=> CartApp.showModal());
            },
            fmtMSS: function(s)
            {
                return(s-(s%=60))/60+(10<s?':':':0')+parseInt(s);
            },
            updateTimer: function() {

                this.timerplayed = this.fmtMSS(parseInt(this.element.currentTime));

            },
            pause: function() {
                if (!this.player.playing) {
                    return;
                }
                this.player.playing = false;
                // this.timer = false;
                this.element.pause();

            },
            play: function() {
                if (this.player.playing) {
                    return;
                }
                
                this.element.currentTime = this.rangeSlider;
                this.player.playing =  true;
                this.element.play();
                this.currentDuration = this.element.duration;

            },
            getTime: function(hms) 
            {
                var a = hms.split(':'); 
                var seconds = ((+a[0]) * 60 + (+a[1])); 
                return seconds;
            },
            setVolume: function() {
                this.element.volume = (this.player.volume / 10);
            },
            curTrack: function() {
                var a;
                for (var i = this.playlist.tracks.length - 1; i >= 0; i--) 
                {
                    a = imageURL + this.playlist.tracks[i].demo_track;
                    if (this.element.src == a )
                    {
                        return i;
                    }
                }
            },
            selectTrack: function(id) {
                this.rangeSlider = .1;
                
                var _this = this;
                for (var i = this.playlist.tracks.length - 1; i >= 0; i--) 
                {
                    if (this.playlist.tracks[i].id == id)
                    {
                        this.element.src = imageURL + this.playlist.tracks[i].demo_track;
                    }
                }
                this.play();
                this.element.play();

                _this.currentID = id;

                setTimeout(function()
                {
                    _this.currentDuration = _this.element.duration;
                    _this.currentDurationText = _this.fmtMSS(_this.currentDuration);
                },1000);

            },
            skipForward: function() {

                track = this.playlist.tracks[this.curTrack()+1].id;
                this.selectTrack(track);
            },
            skipBack: function() 
            {
                if (this.curTrack() > 0) {
                    var track = this.playlist.tracks[this.curTrack()-1].id;
                }
                
                if (this.curTrack() < 0) {
                    var track = this.playlist.tracks[0].id;
                }
                
                this.selectTrack(track);
            },
            toggleRepeat: function() {
                this.player.repeat = !this.player.repeat;
            },
            toggleShuffle: function() {
                this.player.shuffle = !this.player.shuffle;
            }
        }
    })


    // Add Cart 
    // =======================
    var AddCartApp = new Vue({
        el: '#add_cart_modal',
        data: {
            songData:{}
        },
        created() {
            var _this = this;
            jQuery(document).on('click', '#btn-type-payment', function(){
                MainApp.cartData.id = jQuery(this).attr('data-id');
                MainApp.cartData.type = jQuery(this).attr('data-type');
                MainApp.cartData.price = jQuery(this).attr('data-price');
                MainApp.addCart();
                MainApp.fetchCart();
            });

            jQuery(document).on('click', '.add_cart_btn', function(){
                
                _this.songData = PlayerAPP.playlist.tracks[PlayerAPP.curTrack()];

                setTimeout(function(){
                    jQuery('#add_cart_modal').modal('show');
                }, 300);

            });
        },
        methods: {
            getCartModal: function()
            {
                MainApp.fetchCart().then(element=> jQuery('#cart_modal').modal('show'));
                jQuery('#add_cart_modal').modal('hide');
            }
        }
    })


    // Cart Payment 
    // =======================
    var CartApp = new Vue({
        el: '#cart_modal',
        data: {
            cartData:{userData:{name:'10',id:0,leases:{PRO_STAGE:''}}},
        },
        created() {
            var _this = this;
            jQuery(document).on('click', '#getCartbtn', function(){
                MainApp.fetchCart();
                console.log(_this.userData);
            });
        },
        methods: 
        {
            removeItem: function(id, audioid)
            {
                $.post(rootURL+'endpoints/remove_from_cart?&hash_id=' + getHashID(), {
                    audioid: audioid,
                    filter_type: 'removeCartItem',
                    id: id
                }, function (data) {
                    if (data.status == 200) {
                        console.log(data);
                        jQuery('.item-container-' + id).remove();
                        MainApp.fetchCart();
                        // window.location.reload();
                    }else{
                        jQuery('#output-general-errors').text(data.message);
                        element.attr('disabled', false);
                    }
                });

                return false;

            },
            showModal: function()
            {
                jQuery('#cart_modal').modal('show');
                
                if (!this.cartData.userData )
                {
                    this.cartData.userData = {
                        name: '', 
                        lastname: '' 
                    };
                }
                
                $.ajaxSetup({
                  data:'',
                  cache: true
                });

                $.getScript({
                    url: 'https://www.paypal.com/sdk/js?client-id=AWUCfAscLBoB7BKCucyRck6S2W7z-g2pc_ZHiel1s09uGdU50YdnpdaxT8otmNN049oBnltEpDZlWBOU',
                    cache: true
                })
                .done(function( script, textStatus ) {

                  paypal.Buttons({
                      createOrder: function(data, actions) {
                        // This function sets up the details of the transaction, including the amount and line item details.
                        return actions.order.create({
                          purchase_units: [{
                            amount: {
                              value: CartApp.cartData.cost,
                            }
                          }]
                        });
                      },
                      onApprove: function(data, actions) {
                          // This function captures the funds from the transaction.
                          return actions.order.capture().then(function(details) 
                          {

                                jQuery('#output-general-errors').text('Please wait...');

                            $.post(rootURL + '/endpoints/submit_order?hash_id=' + MainApp.hashID , {
                                  data: details,
                                  order_fields: $("form#submitOrder").serialize()
                              }, function (data) {

                                  jQuery('#output-general-errors').text('PAYMENT WAS MADE SUCCESSFULLY, Please wait...');
                                  
                                  if (data.status == 200) {
                                      jQuery('#output-general-errors').text(data.message);
                                      window.location = data.url;
                                  }else{
                                      jQuery('#output-general-errors').text(data.message);
                                  }
                              });
                            
                            // This function shows a transaction success message to your buyer.
                          });
                      }
                    }).render('#paypal-button-container');
                })
                .fail(function( jqxhr, settings, exception ) {
                  // window.location.reload();
                  console.log(jqxhr);
                  console.log(settings);
                  console.log(exception);
                }); ;


                $(document).ready(function() {
                  $.ajaxSetup({ 
                    data: {
                        hash_id: MainApp.hashID
                    },
                    cache: false 
                  });
                });

            }
        }
    })