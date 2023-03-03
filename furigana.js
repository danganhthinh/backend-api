let Kuroshiro = require("kuroshiro")
const KuromojiAnalyzer = require("kuroshiro-analyzer-kuromoji");
require('dotenv').config()
const axios = require("axios");


const url_question = process.env.APP_URL === undefined ? 'http://127.0.0.1:8000/question-furigana' : process.env.APP_URL + '/question-furigana'

function furigana() {
    const kuroshiro = new Kuroshiro();
    const kuroshiroAnalyzer = new KuromojiAnalyzer();
    axios.get(url_question).then(function (response) {
        response.data.data.questions.forEach((element, index) => {
            let id = element.id
            let content = element.content
            let answer1 = element.answer1
            let answer2 = element.answer2
            let answer3 = element.answer3
            let answer4 = element.answer4

            kuroshiro.init(kuroshiroAnalyzer ,id, content, answer1, answer2, answer3, answer4)
                .then(function(){
                    let content_furigana = kuroshiro.convert(content, {mode:"furigana", to:"hiragana"});
                    let answer1_furigana = kuroshiro.convert(answer1, {mode:"furigana", to:"hiragana"});
                    let answer2_furigana = kuroshiro.convert(answer2, {mode:"furigana", to:"hiragana"});
                    let answer3_furigana = kuroshiro.convert(answer3, {mode:"furigana", to:"hiragana"});
                    let answer4_furigana = kuroshiro.convert(answer4, {mode:"furigana", to:"hiragana"});

                    return furigana_text = {
                        'id': id,
                        'content': content_furigana,
                        'answer1': answer1_furigana,
                        'answer2': answer2_furigana,
                        'answer3': answer3_furigana,
                        'answer4': answer4_furigana,
                    }
                })
                .then(async function(result){
                    axios.post(process.env.APP_URL === undefined ? 'http://127.0.0.1:8000/update-question-furigana' : process.env.APP_URL + '/update-question-furigana', {
                        id: result.id,
                        content: await result.content,
                        answer1: await result.answer1,
                        answer2: await result.answer2,
                        answer3: await result.answer3,
                        answer4: await result.answer4
                    }).then(function(response) {
                        console.log('done')
                    }).catch(function(error) {
                        //console.log(error)
                    })

                })
        })
    }).catch(function (err) {
    })
}
setInterval(() => {
    furigana()
}, 6000)








