/**
 * Initialise le moteur de QCM
 * @param {Object} options
 * @param {Object} selectors
 * @constructor
 */
var QCM = function(options, selectors) {
    /**
     * Options de la classe
     */
    this.options = {
        /**
         * Nombre de questions affichées à l'écran à l'instant
         * @type {number}
         */
        questionNumber: 0,

        /**
         * On affichera par défaut n questions à l'écran
         * @type {number}
         */
        defaultQuestionNumber: 1
    };

    /**
     * Sélecteurs CSS des éléments HTML
     * @type {{form: string, container: string, template: string, btnAddQuestion: string, btnDeleteQuestion: string}}
     */
    this.selectors = {
        /**
         * Formulaire
         */
        form: '',

        /**
         * Container des questions
         */
        container: '',

        /**
         * Template d'une question
         */
        template: '',

        /**
         * Bouton pour ajouter une question
         */
        btnAddQuestion: '',

        /**
         * Bouton pour supprimer la question associée
         */
        btnRemoveQuestion: ''
    }

    this.options = $.extend(this.options, options);
    this.selectors = $.extend(this.selectors, selectors);

    this.setUpSelectors();
};

/**
 * Initialise les selecteurs jQuery en fonction des sélecteurs CSS définis
 */
QCM.prototype.setUpSelectors = function() {
    for(selector in this.selectors) {
        this['$' + selector] = $( this.selectors[selector] );
    }

    this.$body = $('html, body');
};

/**
 * :-)
 */
QCM.prototype.init = function() {

    var self = this;

    // On parse le template maintenant afin d'avoir de meilleures performances pour plus tard
    Mustache.parse(this.$template.html());

    // On affiche les "formulaires de questions" par défaut
    this.setUpDefaultQuestions();

    // Le clic sur le bouton "Ajouter question" doit rajouter une question
    this.$form.on('click', this.selectors.btnAddQuestion, function(e) {
        self.addQuestion.apply(self, [e, true]);
    });

    // Le clic sur un bouton "Supprimer question" doit supprimer la question... en question MDRRR ;)))
    this.$form.on('click', this.selectors.btnRemoveQuestion, function(e) {
        self.deleteQuestion.apply(self, [e, $(this)]);
    })

    $(document).on('submit', this.selectors.form, function(e) {
       if(self.options.questionNumber < 1) {
           e.preventDefault();
           return alert("Le QCM doit être constitué d'au moins une question");
       }
    });
};

/**
 * Affiche les formulaires des questions qui doivent être affichés par défaut
 */
QCM.prototype.setUpDefaultQuestions = function() {
    for(var i = 0; i < this.options.defaultQuestionNumber; i++) {
        this.addQuestion(false);
    }
}

/**
 * Affiche un nouveau formulaire de question
 * @param {event} event
 * @param {boolean} animation Est-ce qu'on doit afficher une animation ou pas ?
 */
QCM.prototype.addQuestion = function(event, animation) {
    var $div = $('<div>')
        .attr('id', 'question-' + this.options.questionNumber)
        .addClass('question')
        .hide()
        .html(
            Mustache.render(this.$template.html(), {
                questionNumber: this.options.questionNumber,
                questionNumberDisplay: this.options.questionNumber + 1,
                answers: [0, 1, 2, 3] // 4 réponses par questions
            })
        )
        .appendTo(this.$container);

    this.options.questionNumber++;

    if(animation) {
        $div.slideDown(250);
        this.$body.animate({ scrollTop: $div.offset().top }, 250);
    } else {
        $div.show();
    }
};

/**
 * Supprime la question lié à l'élément $el
 * @param event
 * @param $el
 */
QCM.prototype.deleteQuestion = function(event, $el) {
    var $question = $el.parents('.question');
    var $nextQuestion = $question.next();

    // Le QCM doit avoir au minimum une question
    if(this.options.questionNumber - 1 <= 0) {
        return alert("Le QCM doit être constitué d'au moins une question");
    }

    $question.slideUp(250, function() {
        $(this).remove();
    });

    this.$body.animate({scrollTop: $question.prev().top}, 250);

    this.options.questionNumber--;

    // On met à jour les questions suivantes
    while($nextQuestion.length != 0) {
        var id = $nextQuestion.attr('id').replace('question-', '') - 1;

        $nextQuestion.attr('id', 'question-' + id);
        $nextQuestion.find('.questionNumberDisplay').text(id + 1); // On compte à partir de 1

        $nextQuestion = $nextQuestion.next();
    }
};
