const app = new Vue({
    el: '#runApi',
    data() {
        return {
            dataLoaded: false,
            checkUserGivenValue: null,
            params: [],
            data: []
        }
    },
    mounted() {
        var self = this;
        axios.get(serverURI+'/checkUserGivenValue/' + id).then(function (res) {
            console.log(res.data)
            if (res.data.userGivenValue === true) {
                self.checkUserGivenValue = true;
                self.params = res.data.params;
                console.log(self.params)
            } else if (res.data.userGivenValue === false) {
                self.checkUserGivenValue = false;
                self.data = JSON.parse(res.data.data);
            } else {
                alert('error occurred. Check your JSON data!');
                return;
            }
            self.dataLoaded = true;
        }).catch(function (err) {
            console.log(err)
            self.dataLoaded = false;
            alert('Error occurred while fetching your data!')
        });
    },
    methods:
        {
            /**
             * Submitting user given values with api id and parameter id(s)
             */
            submitUserGivenValues: function () {
                let self = this;
                self.dataLoaded = false;
                axios.post(serverURI+'/submitUserGivenValues/' + id, self.params).then(function (res) {
                    console.log(res.data)
                    self.checkUserGivenValue = false;
                    self.data = JSON.parse(res.data.data);
                    self.dataLoaded = true;
                }).catch(function (err) {
                    console.log(err)
                    self.dataLoaded = false;
                    alert('Error occurred while fetching your data!')
                });
            }
        }

});
