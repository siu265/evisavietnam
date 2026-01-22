const op_settings = window.wc.wcSettings.getSetting('onepay_data', {});

const op_label = window.wp.htmlEntities.decodeEntities(op_settings.title);

const OP_Content = () => {
    return window.wp.htmlEntities.decodeEntities(op_settings.description || '');
};

const IconOP = () => {
    return op_settings.icon
        ? wp.element.createElement('img', { src: op_settings.icon, style: { float: 'right', marginRight: '10px',marginLeft: '10px',height: '40px', maxHeight:'40px' } }) 
        : '';
};
const LabelOP = () => {
    return wp.element.createElement('span', { style: { width: '100%',lineHeight:'2.5'} }, op_label, wp.element.createElement(IconOP));
};


const OP_Block_Gateway = {
    name: 'onepay',
    label: wp.element.createElement(LabelOP),
    content: Object(window.wp.element.createElement)(OP_Content, null),
    edit: Object(window.wp.element.createElement)(OP_Content, null),
    canMakePayment: () => true,
    ariaLabel: op_label,
    supports: {
        features: op_settings.supports,
    },
};

window.wc.wcBlocksRegistry.registerPaymentMethod(OP_Block_Gateway);