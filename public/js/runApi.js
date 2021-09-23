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
        axios.get(serverURI +'/checkUserGivenValue/' + id).then(function (res) {
            console.log(res.data)
            if (res.data.userGivenValue === true) {
                self.checkUserGivenValue = true;
                self.params = res.data.params;
                console.log(self.params)
            } else if (res.data.userGivenValue === false) {
                self.checkUserGivenValue = false;
                self.data = JSON.parse(res.data.data);

                /*custome retirement date*/
                function insertKey(key, value, obj, pos) {
                    return Object.keys(obj).reduce((ac, a, i) => {
                        if (i === pos) ac[key] = self.prl(value)
                        ac[a] = obj[a];
                        return ac;
                    }, {})
                }

                self.data = insertKey('Retirement_Date', self.data.DateOfBirth, self.data, 14);
                /*custome retirement date*/
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
    computed: {},
    methods:
        {
            /**
             * Submitting user given values with api id and parameter id(s)
             */
            submitUserGivenValues: function () {
                let self = this;
                self.dataLoaded = false;
                axios.post(serverURI +'/submitUserGivenValues/' + id, self.params).then(function (res) {
                    console.log(res.data)
                    self.checkUserGivenValue = false;
                    self.data = JSON.parse(res.data.data);
                    // self.data.retirementDate = self.data.DateOfBirth;

                    /*custome retirement date*/
                    function insertKey(key, value, obj, pos) {
                        return Object.keys(obj).reduce((ac, a, i) => {
                            if (i === pos) ac[key] = self.prl(value)
                            ac[a] = obj[a];
                            return ac;
                        }, {})
                    }


                    self.data = insertKey('Retirement_Date', self.data.DateOfBirth, self.data, 14);
                    /*custome retirement date*/

                    self.dataLoaded = true;
                }).catch(function (err) {
                    console.log(err)
                    self.dataLoaded = false;
                    alert('Error occurred while fetching your data!')
                });
            },
            prl: function (dob) {
                let data = dob;
                let DateParts = data.split("/");
                let date = new Date(+DateParts[2], DateParts[1] - 1, +DateParts[0])
                let returningDate = new Date(date.setYear(date.getFullYear() + 60));
                returningDate.setDate(returningDate.getDate()-1);
                return this.convertDate(returningDate);
            },
            convertDate: function (inputFormat) {
                function pad(s) {
                    return (s < 10) ? '0' + s : s;
                }

                var d = new Date(inputFormat)
                return [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/')
            }
        }

});
