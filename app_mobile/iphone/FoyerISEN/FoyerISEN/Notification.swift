//
//  Notification.swift
//  FoyerISEN
//
//  Created by Renald Morice on 27/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class Notification: NSObject {
    var id_notification : Int!
    var id_command : Int!
    var notification : String!
    var login : String!
    var date : NSDate!
    var method : Int!
    
    init(id_notification: Int, id_command: Int, notification: String, login: String, date: NSDate, method: Int){
        super.init()
        
        self.id_notification = id_notification
        self.id_command = id_command
        self.notification = notification
        self.login = login
        self.date = date
        self.method = method
    }
    
}
