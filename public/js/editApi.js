const app = new Vue({
    el: '#editApi',
    data() {
        return {
            validationErrors: [],
            dataLoaded: false,
            apiData: {},
            reference_apis: []
        }
    },
    mounted() {
        var self = this;
        axios.get(serverURI + '/getApi/' + apiId).then(function (res) {
            console.log(res.data)
            self.apiData = res.data.apiData
            self.reference_apis = res.data.reference_apis
            self.dataLoaded = true

        }).catch(function (err) {
            alert('Error occurred while fetching your data!')
        });
    },
    methods:
        {
            newParameter: function () {
                this.apiData.keys.push({
                    "key": "",
                    "value": "",
                    "option": "0",
                    "reference_api_id": "",
                    "reference_api_response_key": "",
                })
            },
            validate: function () {
                let self = this;
                self.validationErrors = [];
                let fields = ['name', 'url', 'type', 'request_type'];
                fields.forEach(function (item) {
                    if (self.apiData[item] === "") {
                        self.validationErrors.push((item + ' is required!').toUpperCase())
                    }
                });
                if (!self.validationErrors.length) {
                    self.update()
                } else {
                    alert('Please Fill all required Fields')
                }
            },
            update: function () {
                var self = this;
                axios.post(serverURI + '/updateApi/' + apiId, self.apiData).then(function (res) {
                    alert('API Updated successfully!')
                    window.location.href = serverURI + '/home';
                }).catch(function (err) {
                    alert('Something went wrong!')
                });
            }
        }

});
