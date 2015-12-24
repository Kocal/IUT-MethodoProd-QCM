var QCM;

QCM = (function() {
  function QCM(options, selectors) {
    this.options = {
      questionsNumber: 0,
      defaultQuestionsNumber: 1,
      answersNumberPerQuestion: 4
    };
    this.selectors = {
      form: '',
      container: '',
      template: '',
      btnAddQuestion: '',
      btnRemoveQuestion: ''
    };
    this.messages = {
      NOT_ENOUGH_QUESTIONS: "Le QCM doit être constitué d'au moins d'une question"
    };
    this.options = Object.assign({}, this.options, options);
    this.selectors = Object.assign({}, this.selectors, selectors);
    return;
  }

  QCM.prototype.init = function() {
    var i, ref, results;
    this.answers = (function() {
      results = [];
      for (var i = 0, ref = this.options.answersNumberPerQuestion; 0 <= ref ? i <= ref : i >= ref; 0 <= ref ? i++ : i--){ results.push(i); }
      return results;
    }).apply(this);
    this._setUpSelectors();
    this._parseTemplate();
    this._setUpDefaultQuestions();
    this._setUpEvents();
  };

  QCM.prototype._setUpSelectors = function() {
    var i, len, ref, selector, setup;
    setup = (function(_this) {
      return function(selector) {
        return _this['$' + selector] = $(_this.selectors[selector]);
      };
    })(this);
    ref = Object.keys(this.selectors);
    for (i = 0, len = ref.length; i < len; i++) {
      selector = ref[i];
      setup(selector);
    }
    this.$body = $(document.body);
  };

  QCM.prototype._parseTemplate = function() {
    return Mustache.parse(this.$template.html());
  };

  QCM.prototype._setUpDefaultQuestions = function() {
    var i, ref;
    for (i = 0, ref = this.options.defaultQuestionsNumber; 0 <= ref ? i < ref : i > ref; 0 <= ref ? i++ : i--) {
      this.addQuestion(null, false);
    }
  };

  QCM.prototype._setUpEvents = function() {
    this.$form.on('click', this.selectors.btnAddQuestion, (function(_this) {
      return function(event) {
        return _this.addQuestion.apply(_this, [event, true]);
      };
    })(this));
    this.$form.on('click', this.selectors.btnRemoveQuestion, (function(_this) {
      return function(event) {
        return _this.deleteQuestion.apply(_this, [event]);
      };
    })(this));
    $(document).on('submit', this.selectors.form, (function(_this) {
      return function(event) {
        if (_this.options.questionsNumber < 1) {
          event.preventDefault();
          return alert(_this.messages.NOT_ENOUGH_QUESTIONS);
        }
      };
    })(this));
  };

  QCM.prototype.addQuestion = function(event, animate) {
    var $div;
    $div = $('<div>').data('question', this.options.questionsNumber).addClass('question').hide().html(Mustache.render(this.$template.html(), {
      questionNumber: this.options.questionsNumber,
      questionNumberDisplay: this.options.questionsNumber + 1,
      answers: this.answers
    })).appendTo(this.$container);
    if (animate) {
      $div.slideDown(250);
      this.$body.animate({
        scrollTop: $div.offset().top
      }, 250);
    } else {
      $div.show();
    }
    this.options.questionsNumber++;
  };

  QCM.prototype.deleteQuestion = function(event) {
    var $element, $nextQuestion, $question, newId;
    $element = $(event.currentTarget);
    $question = $element.parents('.question');
    $nextQuestion = $question.next();
    if (this.options.questionNumber - 1 <= 0) {
      event.preventDefault();
      return alert(this.messages.NOT_ENOUGH_QUESTIONS);
    }
    this.options.questionsNumber--;
    $question.slideUp(250, $question.remove);
    while ($nextQuestion.length !== 0) {
      newId = parseInt($nextQuestion.data('question', 10)) - 1;
      $nextQuestion.data('question', newId);
      $nextQuestion.find('.questionNumberDisplay').text(newId + 1);
      $nextQuestion = $nextQuestion.find();
    }
  };

  return QCM;

})();

//# sourceMappingURL=compiled.coffee.js.map
