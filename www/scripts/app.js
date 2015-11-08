/* global $ */
var gplusFlickr = gplusFlickr || {};

(function () {
	var Batch = function () {
		this.batch = [];
		this.started = {};
		this.finished = {};
	};

	Batch.prototype = {
		/**
		 * Add callback to thread
		 *
		 * @param callback
		 */
		add: function (callback) {
			this.batch.push(callback);
		},
		/**
		 * More than one thread can be excuted
		 *
		 * @param count
		 */
		execute: function (count) {
			var deferred = $.Deferred();

			if (!count) {
				count = 1;
			}

			for (var i = 0; i < count; i++) {
				this.next(deferred);
			}
			return deferred.promise();
		},
		/**
		 *
		 * @param {$.Deferred} deferredFinish
		 */
		next: function (deferredFinish) {
			var self = this;

			$.each(this.batch, function (index, callback) {
				if (self.started[index] === undefined) {
					self.started[index] = true;
					callback().done(function () {
						self.finished[index] = true;
						self.next(deferredFinish);
					});
					return false;
				}
			});
			if (this.isFinished() && deferredFinish) {
				this.finished = true;
				deferredFinish.resolve();
			}
		},
		/**
		 * @returns {boolean}
		 */
		isFinished: function () {
			var finishedCount = Object.keys(this.finished).length;
			return finishedCount === this.batch.length;
		}
	};

	gplusFlickr.Batch = Batch;

	var AlbumController = function () {};

	AlbumController.prototype = {
		handle: function () {
			var menu, links;

			menu = $('.menu');
			links = $('.photos .photo .findLink');

			this.initMenu(menu, links);
		},
		initMenu: function (menu, links) {
			var self = this,
				activeClass = 'active',
				seekButton = menu.find('.seekFlickr'),
				hideButton = menu.find('.hideFound'),
				uploadButton = menu.find('.uploadLink');

			uploadButton.hide();
			seekButton.click(function (event) {
				event.preventDefault();

				if (!seekButton.hasClass(activeClass)) {
					self.batchLoad(links).then(function () {
						seekButton.removeClass(activeClass);
						uploadButton.show();
					});
					seekButton.addClass(activeClass);
				}
			});

			hideButton.click(function (event) {
				var hideClass = 'hide',
					photos = $('.photos'); // TODO inject?

				event.preventDefault();

				if (hideButton.hasClass(activeClass)) {
					photos.removeClass(hideClass);
					hideButton.removeClass(activeClass);
				} else {
					photos.addClass(hideClass);
					hideButton.addClass(activeClass);
				}
			});

		},

		batchLoad: function (anchors) {
			var addLoader,
				batch = new Batch(),
				deferred = $.Deferred();

			addLoader = function(element) {
				element = $(element);
				batch.add(function () {
					var parent = element.closest('.photo'),
						setClass,
						successHandler,
						deferred = $.Deferred();

					setClass = function (className) {
						$.each(['done', 'warning', 'error'], function (i, cName) {
							if (cName !== className) {
								parent.removeClass(cName);
							} else {
								parent.addClass(cName);
							}
						});
					}
					successHandler = function (data) {
						if (data.redirect) {
							$.ajax(data.redirect, {
								success: successHandler
							});
						} else {
							parent.removeClass('loading');
							if (data.photos.length) {
								if (data.photos.length === 1) {
									setClass('done');
								} else {
									setClass('warning');
								}
							} else {
								setClass('error');
							}
							deferred.resolve();
						}
					};

					parent.addClass('loading');
					$.ajax(element.attr('href'), {
						success: successHandler
					});
					return deferred.promise();
				});
			};

			$.each(anchors, function (index, element) {
				addLoader(element);
			});
			batch.execute(5).then(deferred.resolve);
			return deferred.promise();
		}
	};
	gplusFlickr.controllers = {
		album: AlbumController
	};

	gplusFlickr.controller = function (name) {
		var controller, controllerClass = this.controllers[name];

		if (controllerClass) {
			controller = new controllerClass();
			controller.handle();
		}
	};
})();
