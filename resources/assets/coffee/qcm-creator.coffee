class QCM
    constructor: (options, selectors) ->
        @options =
            questionsNumber         : 0,
            defaultQuestionsNumber  : 1,
            answersNumberPerQuestion: 4

        @selectors =
            form             : '',
            container        : '',
            template         : '',
            btnAddQuestion   : '',
            btnRemoveQuestion: '',

        @messages =
            NOT_ENOUGH_QUESTIONS: "Le QCM doit être constitué d'au moins d'une question"

        @options = Object.assign {}, @options, options;
        @selectors = Object.assign {}, @selectors, selectors;

        return

    init: ->
        @answers = [ 0...@options.answersNumberPerQuestion ]
        @_setUpSelectors()
        @_parseTemplate()
        #@_setUpDefaultQuestions()
        @_setUpEvents()

        return

    _setUpSelectors: ->
        setup = (selector) =>
            this[ '$' + selector ] = $ @selectors[ selector ]

        setup selector for selector in Object.keys(@selectors)

        @$body = $ document.body

        return

    _parseTemplate: ->
        Mustache.parse @$template.html()

    _setUpDefaultQuestions: ->
        @addQuestion(null, false) for [ 0...@options.defaultQuestionsNumber ]
        return

    _setUpEvents: ->
        @$form.on 'click', @selectors.btnAddQuestion, (event) =>
            @addQuestion.apply this, [event, true]

        @$form.on 'click', @selectors.btnRemoveQuestion, (event) =>
            @deleteQuestion.apply this, [event]

        $(document).on 'submit', @selectors.form, (event) =>
            if(@options.questionsNumber < 1)
                event.preventDefault()
                alert @messages.NOT_ENOUGH_QUESTIONS

        return

    addQuestion: (event, animate) ->
        $div = $ '<div>'
        .attr 'data-question', @options.questionsNumber
        .addClass 'question'
        .hide()
        .html Mustache.render @$template.html(),
            questionNumber       : @options.questionsNumber,
            questionNumberDisplay: @options.questionsNumber + 1
            answers              : @answers
        .appendTo @$container

        if animate
            $div.slideDown 250
            @$body.animate
                scrollTop: $div.offset().top
            , 250
        else
            $div.show()

        @options.questionsNumber++

        return

    deleteQuestion: (event) ->
        $element = $ event.currentTarget
        $question = $element.parents '.question'
        $nextQuestion = $question.next()

        if @options.questionNumber - 1 <= 0
            event.preventDefault()
            return alert @messages.NOT_ENOUGH_QUESTIONS

        @options.questionsNumber--

        $question.slideUp 250, ->
            $question.remove()

        while $nextQuestion.length != 0
            newId = parseInt($nextQuestion.attr('data-question'), 10) - 1

            $nextQuestion.attr 'data-question', newId
            $nextQuestion.find('.questionNumberDisplay').text newId + 1
            $nextQuestion = $nextQuestion.next()

        return
