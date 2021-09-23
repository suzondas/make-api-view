const app = new Vue({
    el: '#newApi',
    data() {
        return {
            apiData: {
                "url": "",
                "type": "token",
                "request_type": "get",
                "name": "",
                "keys": [
                    {
                        "key": "",
                        "value": "",
                        "option": "0",
                        "reference_api_id": "",
                        "reference_api_response_key": "",
                    }
                ]
            },
            reference_apis: JSON.parse(reference_apis),
            reference_api_keys: [],
            validationErrors: []
        }
    },
    mounted() {

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
                    self.save()
                } else {
                    alert('Please Fill all required Fields')
                }
            },
            save: function () {
                var self = this;
                axios.post('saveApi', self.apiData).then(function (res) {
                    alert('API created successfully!')
                    window.location.href = '/home';
                }).catch(function (err) {
                    alert('Something went wrong!')
                });
            }
        }

});
