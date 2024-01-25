import BaseView from 'oroui/js/app/views/base/view';
import $ from 'jquery';
import messenger from 'oroui/js/messenger';
import mediator from 'oroui/js/mediator';
import routing from 'routing';
import __ from 'orotranslation/js/translator';

const AjaxButtonView = BaseView.extend({
    product: null,
    events: {
        'click .favorite-button-ajax': 'setFavoriteProduct',
    },

    initialize(options) {
        this.options = {...this.options, ...options};
        this.product = this.options.productModel;
    },

    setFavoriteProduct(e) {
        let self = this;
        e.preventDefault();
        $.ajax({
            url: `/favorite/create/${this.product.id}`,
            type: 'POST',
            success: function(response) {
                if (response.message && response.status) {

                    const status = self.getStatus(response.status);
                    messenger.notificationFlashMessage(status, response.message);
                    self.updateIcon($(e.currentTarget), response.status);

                    mediator.trigger('datagrid:doRefresh:synolia-favorite-grid');
                }
            },
            error: function() {
                messenger.notificationFlashMessage('error',  _.__('synolia_favorite_bundle.notification.error'));
            }
        });
    },

    getStatus(params) {
        return (['full', 'empty'].indexOf(params) > -1) ? 'success' : params;
    },

    updateIcon(target, status) {
        const icon = {
            full: 'fa-heart',
            empty: 'fa-heart-o'
        };
        if(icon.hasOwnProperty(status)) {
            $(target)
                .children('i')
                .removeClass('fa-heart')
                .removeClass('fa-heart-o')
                .addClass(`${icon[status]}`);
        }
    }
});

export default AjaxButtonView
