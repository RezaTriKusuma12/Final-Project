// /Login/js/main.js
const publicVapidKey = "BNjQAYjndHgnLgOBghrnaZJgMX3XUCoO4HhxR9ZGvl5PLYXYfkJW1lNwVospLvRrSkjsAIS_S2GyMtZDc9ztwRA";

async function initPush() {
  if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
    console.log("Browser tidak mendukung Web Push");
    return;
  }

  try {
    const permission = await Notification.requestPermission();
    if (permission !== "granted") {
      console.log("Izin notifikasi ditolak");
      return;
    }

    const register = await navigator.serviceWorker.register("/Login/service-worker.js");
    const existing = await register.pushManager.getSubscription();
    if (existing) {
      console.log("Sudah subscribe");
      // send existing subscription to server in case DB was cleared
      await sendSubscriptionToServer(existing);
      return;
    }

    const subscription = await register.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
    });

    await sendSubscriptionToServer(subscription);
    console.log("Berhasil subscribe push");
  } catch (err) {
    console.error("Gagal register push:", err);
  }
}

async function sendSubscriptionToServer(subscription) {
  try {
    const res = await fetch("/login/save_subscription.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(subscription)
    });
    return res;
  } catch (e) {
    console.error("Gagal mengirim subscription ke server", e);
  }
}

function urlBase64ToUint8Array(base64String) {
  const padding = "=".repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding).replace(/\-/g, "+").replace(/_/g, "/");
  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

// otomatis dipanggil saat halaman diload
initPush();
