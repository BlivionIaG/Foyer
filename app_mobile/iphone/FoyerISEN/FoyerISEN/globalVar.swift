//
//  globalVar.swift
//  FoyerISEN
//
//  Created by Renald Morice on 22/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import Foundation

/*  Variables globales  */
/*---------------------*/
let notificationsCenter = NSNotificationCenter.defaultCenter()
let productsManager = ProductManager.sharedInstance
let banniereManager = BanniereManager.sharedInstance
let networkManager = NetworkManager.sharedInstance
let commandManager = CommandManager.sharedInstance

var user: String?

struct MyNotifications{
    static let productImageDownloaded = "productImageDownloaded"
    static let productsDownloaded = "productsDownloaded"
    static let banniereDownloaded = "banniereDownloaded"
}

struct MyHexaColors{
    static let green = 0x00994C
    static var red = 0xC63D3D
}

