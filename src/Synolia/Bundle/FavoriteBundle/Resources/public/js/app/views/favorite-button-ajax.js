import BaseView from 'oroui/js/app/views/base/view';
import $ from 'jquery';
import messenger from 'oroui/js/messenger';
import mediator from 'oroui/js/mediator';

const AjaxButtonView = BaseView.extend({

    product: null,

    events: {
        'click .btn': 'setFavoriteProduct',
    },

    initialize(options) {
        this.options = {...this.options, ...options};
        this.product = this.options.product
    },

    setFavoriteProduct(e) {
        let self = this;
        e.preventDefault();

        $.ajax({
            url: `/favorite/create/${this.product.id}`,
            method: 'POST',
            success: function(response) {
                if (response.message && response.status) {

                    const status = self.getStatus(response.status);
                    messenger.notificationFlashMessage(status, response.message);

                    $(e.currentTarget).html(self.getHeart(response.status));

                    mediator.trigger('datagrid:doRefresh:synolia-favorite-grid');
                }
            },
            error: function() {
                messenger.notificationFlashMessage('error', 'Oops');
            }
        });
    },

    getStatus(params) {
        return (['full', 'empty'].indexOf(params) > -1) ? 'success' : params;
    },

    getHeart(params) {
        return (params === 'full') ? '♥' : '♡';
    }
});

export default AjaxButtonView