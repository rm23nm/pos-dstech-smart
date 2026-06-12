package com.example.absensikaryawan

import android.Manifest
import android.annotation.SuppressLint
import android.content.pm.PackageManager
import android.os.Bundle
import android.webkit.GeolocationPermissions
import android.webkit.PermissionRequest
import android.webkit.WebChromeClient
import android.webkit.WebSettings
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.activity.ComponentActivity
import androidx.activity.result.contract.ActivityResultContracts
import androidx.core.content.ContextCompat
import android.view.ViewGroup.LayoutParams

class MainActivity : ComponentActivity() {

    private lateinit var webView: WebView
    
    // Store pending requests
    private var pendingGeoCallback: GeolocationPermissions.Callback? = null
    private var pendingGeoOrigin: String? = null
    private var pendingCameraRequest: PermissionRequest? = null

    private val requestLocationPermissionLauncher =
        registerForActivityResult(ActivityResultContracts.RequestMultiplePermissions()) { permissions ->
            val granted = permissions.entries.all { it.value }
            if (granted) {
                pendingGeoCallback?.invoke(pendingGeoOrigin, true, false)
            } else {
                pendingGeoCallback?.invoke(pendingGeoOrigin, false, false)
            }
            pendingGeoCallback = null
            pendingGeoOrigin = null
        }

    private val requestCameraPermissionLauncher =
        registerForActivityResult(ActivityResultContracts.RequestPermission()) { isGranted ->
            if (isGranted) {
                pendingCameraRequest?.grant(pendingCameraRequest?.resources)
            } else {
                pendingCameraRequest?.deny()
            }
            pendingCameraRequest = null
        }

    @SuppressLint("SetJavaScriptEnabled")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        
        webView = WebView(this).apply {
            layoutParams = LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT)
            settings.javaScriptEnabled = true
            settings.domStorageEnabled = true
            settings.setGeolocationEnabled(true)
            settings.mediaPlaybackRequiresUserGesture = false
            settings.cacheMode = WebSettings.LOAD_DEFAULT
            
            webViewClient = WebViewClient()
            webChromeClient = object : WebChromeClient() {
                override fun onGeolocationPermissionsShowPrompt(
                    origin: String,
                    callback: GeolocationPermissions.Callback
                ) {
                    val fineLoc = ContextCompat.checkSelfPermission(this@MainActivity, Manifest.permission.ACCESS_FINE_LOCATION)
                    if (fineLoc != PackageManager.PERMISSION_GRANTED) {
                        pendingGeoCallback = callback
                        pendingGeoOrigin = origin
                        requestLocationPermissionLauncher.launch(
                            arrayOf(Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION)
                        )
                    } else {
                        callback.invoke(origin, true, false)
                    }
                }

                override fun onPermissionRequest(request: PermissionRequest) {
                    if (request.resources.contains(PermissionRequest.RESOURCE_VIDEO_CAPTURE)) {
                        val cameraPerm = ContextCompat.checkSelfPermission(this@MainActivity, Manifest.permission.CAMERA)
                        if (cameraPerm != PackageManager.PERMISSION_GRANTED) {
                            pendingCameraRequest = request
                            requestCameraPermissionLauncher.launch(Manifest.permission.CAMERA)
                        } else {
                            request.grant(request.resources)
                        }
                    } else {
                        request.grant(request.resources)
                    }
                }
            }
        }
        
        setContentView(webView)
        webView.loadUrl("https://pos.dstechsmart.com/login")
    }

    override fun onBackPressed() {
        if (webView.canGoBack()) {
            webView.goBack()
        } else {
            super.onBackPressed()
        }
    }
}
