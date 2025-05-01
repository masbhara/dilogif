const submitOrder = async () => {
    try {
        loading.value = true;
        
        // Log data untuk debugging
        console.log('Form Data:', form);
        console.log('Cart Items:', cartItems.value);
        console.log('Summary:', summary.value);
        
        const response = await axios.post(route('orders.store'), {
            ...form,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        });
        
        console.log('Success Response:', response.data);
        
        // Redirect ke halaman thank you
        window.location.href = route('orders.thankyou', { order: response.data.order_id });
        
    } catch (error) {
        console.error('Error submitting order:', error);
        console.log('Error details:', error.response?.data);
        
        if (error.response?.data?.message) {
            alert(error.response.data.message);
        } else {
            alert('Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    } finally {
        loading.value = false;
    }
}; 