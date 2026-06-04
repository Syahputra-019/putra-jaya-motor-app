<script>
    (() => {
        const payButton = document.getElementById('pay-button');
        const hint = document.getElementById('midtrans-hint');

        if (!payButton) {
            return;
        }

        const tokenUrl = payButton.dataset.tokenUrl;
        const originalButtonText = payButton.textContent;
        const snapScript = document.querySelector('script[data-midtrans-snap]');
        let checkoutData = @json($initialCheckout ?? null);
        let tokenPromise = null;
        let snapPromise = null;
        let shouldRefreshToken = false;

        const setHint = (message) => {
            if (hint) {
                hint.textContent = message;
            }
        };

        const showAlert = (options) => {
            if (window.Swal?.fire) {
                return window.Swal.fire(options);
            }

            window.alert(options.text || options.title || 'Informasi pembayaran Midtrans.');
            return Promise.resolve();
        };

        const closeAlert = () => {
            if (window.Swal?.close) {
                window.Swal.close();
            }
        };

        const withRefreshParam = (url, refresh) => {
            if (!refresh) {
                return url;
            }

            const nextUrl = new URL(url, window.location.origin);
            nextUrl.searchParams.set('refresh', '1');
            return nextUrl.toString();
        };

        const loadCheckout = ({ refresh = false } = {}) => {
            const refreshToken = refresh || shouldRefreshToken;

            if (!refreshToken && checkoutData?.snap_token && checkoutData?.redirect_url) {
                return Promise.resolve(checkoutData);
            }

            if (!tokenUrl) {
                return Promise.reject(new Error('URL token Midtrans tidak tersedia.'));
            }

            if (refreshToken) {
                tokenPromise = null;
                shouldRefreshToken = false;
            }

            if (!tokenPromise) {
                tokenPromise = fetch(withRefreshParam(tokenUrl, refreshToken), {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                }).then(async (response) => {
                    const payload = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(payload.message || 'Token Midtrans belum siap.');
                    }

                    if (!payload.snap_token || !payload.redirect_url) {
                        throw new Error('Respons token Midtrans tidak lengkap.');
                    }

                    checkoutData = payload;
                    return checkoutData;
                }).catch((error) => {
                    tokenPromise = null;
                    throw error;
                });
            }

            return tokenPromise;
        };

        const waitForSnap = () => {
            if (window.snap?.pay) {
                return Promise.resolve(window.snap);
            }

            if (!snapScript) {
                return Promise.reject(new Error('Script Midtrans tidak ditemukan di halaman.'));
            }

            if (!snapPromise) {
                snapPromise = new Promise((resolve, reject) => {
                    const resolveIfReady = () => {
                        if (window.snap?.pay) {
                            resolve(window.snap);
                        } else {
                            reject(new Error('Library Snap Midtrans belum aktif setelah script dimuat.'));
                        }
                    };

                    snapScript.addEventListener('load', resolveIfReady, { once: true });
                    snapScript.addEventListener('error', () => {
                        reject(new Error('Script Midtrans gagal dimuat.'));
                    }, { once: true });

                    window.setTimeout(() => {
                        if (window.snap?.pay) {
                            resolve(window.snap);
                        } else {
                            reject(new Error('Script Midtrans masih lambat dimuat.'));
                        }
                    }, 6000);
                });
            }

            return snapPromise;
        };

        const openRedirectPayment = (checkout) => {
            if (!checkout?.redirect_url) {
                throw new Error('URL pembayaran Midtrans tidak tersedia.');
            }

            window.location.href = checkout.redirect_url;
        };

        const openSnapPayment = (checkout) => {
            if (!window.snap?.pay) {
                throw new Error('Library Snap Midtrans belum termuat.');
            }

            window.snap.pay(checkout.snap_token, {
                onSuccess: function() {
                    showAlert({
                        title: 'Pembayaran Berhasil!',
                        text: 'Terima kasih, pembayaran telah kami terima.',
                        icon: 'success',
                        confirmButtonColor: '#0d1f3a',
                        confirmButtonText: 'Lihat Nota'
                    }).then(() => {
                        window.location.href = "{{ route('transaksi.cetak', $transaksi->id) }}";
                    });
                },
                onPending: function() {
                    setHint('Instruksi pembayaran sudah dibuat. Selesaikan pembayaran sesuai arahan Midtrans.');
                    showAlert({
                        title: 'Menunggu Pembayaran',
                        text: 'Silakan selesaikan instruksi pembayaran Anda.',
                        icon: 'info',
                        confirmButtonColor: '#0d1f3a',
                        confirmButtonText: 'Tutup'
                    });
                },
                onError: function() {
                    setHint('Pembayaran Midtrans gagal diproses. Klik tombol bayar untuk mencoba token baru.');
                    checkoutData = null;
                    tokenPromise = null;
                    shouldRefreshToken = true;
                    showAlert({
                        title: 'Pembayaran Gagal!',
                        text: 'Maaf, terjadi kesalahan saat memproses pembayaran.',
                        icon: 'error',
                        confirmButtonColor: '#e11d48',
                        confirmButtonText: 'Tutup'
                    });
                },
                onClose: function() {
                    closeAlert();
                    setHint('Popup Midtrans ditutup. Klik tombol bayar untuk membuka lagi.');
                }
            });
        };

        const setButtonBusy = (busy, label = null) => {
            payButton.disabled = busy;
            payButton.setAttribute('aria-busy', busy ? 'true' : 'false');
            payButton.textContent = label || (busy ? 'Menyiapkan Midtrans...' : originalButtonText);
        };

        const warmupCheckout = async () => {
            setButtonBusy(true, 'Menyiapkan Midtrans...');
            setHint('Menyiapkan token dan popup Midtrans...');

            try {
                await loadCheckout();

                try {
                    await waitForSnap();
                    setHint('Midtrans siap. Klik tombol bayar untuk membuka popup.');
                } catch (snapError) {
                    setHint('Token siap. Jika popup masih lambat, sistem akan membuka halaman pembayaran Midtrans.');
                }
            } catch (error) {
                setHint(error.message || 'Midtrans belum siap.');
            } finally {
                setButtonBusy(false);
            }
        };

        warmupCheckout();

        payButton.addEventListener('click', async () => {
            try {
                setButtonBusy(true);

                let checkout = await loadCheckout();

                try {
                    await waitForSnap();
                    openSnapPayment(checkout);
                } catch (snapError) {
                    if (window.snap?.pay) {
                        openSnapPayment(checkout);
                    } else {
                        setHint('Popup Midtrans tidak dapat dimuat. Membuka halaman pembayaran Midtrans.');
                        openRedirectPayment(checkout);
                    }
                }
            } catch (error) {
                showAlert({
                    title: 'Midtrans belum siap',
                    text: error.message || 'Coba lagi sebentar.',
                    icon: 'warning',
                    confirmButtonColor: '#0d1f3a',
                    confirmButtonText: 'Tutup'
                });
            } finally {
                setButtonBusy(false);
            }
        });
    })();
</script>
