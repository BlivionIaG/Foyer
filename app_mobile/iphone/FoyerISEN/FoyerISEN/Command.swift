//
//  Command.swift
//  FoyerISEN
//
//  Created by Renald Morice on 27/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class Command: NSObject {
    
    var id_command : Int
    var login : String
    var time : NSDate
    var date : NSDate
    var periode_debut : String
    var periode_fin : String
    var product : [Product]
    var total : Int
    
    init(id_command: Int, login: String, time: NSDate, date: NSDate, periode_debut: String, periode_fin: String, product: [Product], total: Int){
        self.id_command = id_command
        self.login = login
        self.time = time
        self.date = date
        self.periode_debut = periode_debut
        self.periode_fin = periode_fin
        self.product = product
        self.total = total
    }

}
