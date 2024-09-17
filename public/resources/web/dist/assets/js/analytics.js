function analyticsInitiateCheckoutEvent({
    transactionId,
    totalPrice,
    currency = "IDR",
    totalTax,
    totalShipping = 0,
    coupon = null,
    items,
    userData = null,
}) {
    // Google
    gtag("event", "begin_checkout", {
        transaction_id: transactionId,
        value: totalPrice,
        currency: currency,
        tax: totalTax,
        shipping: totalShipping,
        coupon: coupon,
        items: items,
        user_data: userData,
    });

    // Facebook
    fbq("track", "InitiateCheckout", {
        transaction_id: transactionId,
        value: totalPrice,
        currency: currency,
        tax: totalTax,
        shipping: totalShipping,
        coupon: coupon,
        user_data: userData,
        items: items,
    });
}

function analyticsLeadEvent({
    userData,
    totalPrice,
    currency = "IDR",
    items = null,
}) {
    // Google
    gtag("event", "generate_lead", {
        value: totalPrice,
        currency: currency,
        items: items,
        user_data: userData,
    });

    // Facebook
    fbq("track", "Lead", userData);
}

function analyticsContactEvent({ contactedInfo }) {
    // Google
    gtag("event", "Contact", contactedInfo);

    // Facebook
    fbq("track", "Contact", contactedInfo);
}

function analyticsLoginEvent({ method, userData }) {
    // Google
    gtag("event", "login", {
        method: method,
        ...userData,
    });

    // Facebook
    fbq("track", "Login", { method, ...userData });
}

function analyticsRegisterEvent({ method, userData }) {
    // Google
    gtag("event", "sign_up", {
        method: method,
        ...userData,
    });

    // Facebook
    fbq("track", "CompleteRegistration", { method, ...userData });
}
