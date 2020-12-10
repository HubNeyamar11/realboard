
export default {
    data(){
        return {

        }
    }, 
    methods: {
        async callApi(method, url, dataObj ){
            try {
              return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response
            }
        }, 

        i(desc, title="테스트") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        s(desc, title="성공!") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        w(desc, title="경고!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        e(desc, title="에러!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        }, 
        swr(desc='뭔가 잘못 됨! 다시!', title="에러!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        }, 
        

    },

    

    
   
}