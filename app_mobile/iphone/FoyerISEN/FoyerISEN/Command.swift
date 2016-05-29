//
//  Command.swift
//  FoyerISEN
//
//  Created by Renald Morice on 27/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class Command: NSObject {
    
    var id_command : Int!
    //var time : NSDate! //Date de la prise de la commande
    var date : NSDate!
    var periode_debut : String!
    var periode_fin : String!
    var product : NSArray!
    var total : Int!
    var state : Int!
    
    init(id_command: Int, date: NSDate, periode_debut: String, periode_fin: String, product: NSArray, total: Int, state: Int){
        super.init()
        
        self.id_command = id_command
        //self.time = time
        self.date = date
        self.periode_debut = periode_debut
        self.periode_fin = periode_fin
        self.product = product
        self.total = total
        self.state = state
    }

}
