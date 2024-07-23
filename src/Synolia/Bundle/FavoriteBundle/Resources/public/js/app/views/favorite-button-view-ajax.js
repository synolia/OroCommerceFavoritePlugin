import BaseView from 'oroui/js/app/views/base/view';
import mediator from 'oroui/js/mediator';
import messenger from 'oroui/js/messenger';
import routing from 'routing';
import $ from 'jquery';

const PRODUCT_LISTING = 'productListing';
const FAVORITE_PAGE = 'favoritePage';
const ROUTE_FAVORITE_BUTTON_AJAX_UPDATE = 'synolia_favorite_button_ajax_update';
const FAVORITE_DATAGRID_NAME = 'synolia-favorite-grid';
const ICONS = {
    created: 'fa-heart',
    removed: 'fa-heart-o'
};

const FavoriteButtonViewAjax = BaseView.extend({
    product: null,

    origin: PRODUCT_LISTING,

    events: {
        'click .favorite-button__link': 'setProductToFavorite'
    },

    constructor: function FavoriteButtonViewAjax(options) {
        FavoriteButtonViewAjax.__super__.constructor.call(this, options);
    },

    initialize(options) {
        this.options = {...this.options, ...options};
        this.origin = [PRODUCT_LISTING, FAVORITE_PAGE].includes(this.options.origin)
            ? this.options.origin
            : this.origin;
        this.product = this.options.productModel;

        FavoriteButtonViewAjax.__super__.initialize.call(this, options);
    },

    setProductToFavorite(e) {
        e.preventDefault();
        mediator.execute('showLoading');
        const $target = $(e.currentTarget);

        let id = this.product.attributes.parentProduct
            ? this.product.attributes.parentProduct
            : this.product.attributes.id;

        if (typeof $target.attr('product-id') !== 'undefined') {
            id = $target.attr('product-id');
        }

        const self = this;
        $.ajax({
            url: routing.generate(ROUTE_FAVORITE_BUTTON_AJAX_UPDATE, {id: id}),
            type: 'POST',
            success: response => {
                if (response.message && response.status) {
                    const status = self.getStatus(response.status);
                    messenger.notificationMessage(status, response.message, {delay: 2000});

                    self.updateIcon($target, response.success, response.status);
                    self.updateDatagrid();
                }
            },
            complete: function() {
                mediator.execute('hideLoading');
            }
        });
    },

    getStatus(param) {
        return (['removed', 'created'].includes(param) ? 'success' : param);
    },

    updateDatagrid: function() {
        if (this.origin === FAVORITE_PAGE) {
            mediator.trigger('datagrid:doRefresh:' + FAVORITE_DATAGRID_NAME);
        }
    },

    updateIcon: function(target, success, status) {
        if (!success || !['removed', 'created'].includes(status)) {
            return;
        }

        $(target)
            .children('i')
            .removeClass('fa-heart')
            .removeClass('fa-heart-o')
            .addClass(`fa ${ICONS[status]}`);
    }
});

export default FavoriteButtonViewAjax;
