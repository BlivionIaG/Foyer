//
//  CommandCell.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class CommandCell: UITableViewCell {
    
    /*----------  VARIABLES  ----------*/
    var command: Command!
    
    @IBOutlet weak var numCommandLabel: UILabel!
    @IBOutlet weak var totalCommandLabel: UILabel!
    @IBOutlet weak var datePeriodCommandLabel: UILabel!
    @IBOutlet weak var stateCommandLabel: UILabel!
    /*--------------------------------*/
    
    //Chargement de la cellule
    //------------------------
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }
    
    
    //Quand une cellule est selectionnée
    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
        
    }
    
    
    //Gérer l'affichage d'une cellule
    //-------------------------------
    func setCommandCell(command: Command){
        
        self.command = command
        let dateFormatter = NSDateFormatter()
        dateFormatter.dateFormat = "dd-MM-yyyy"
        
        self.numCommandLabel.text = "Commande n° \(String(self.command.id_command))"
        
        self.totalCommandLabel.text = "\(String(self.command.total)) €"
        
        self.datePeriodCommandLabel.text = "\(dateFormatter.stringFromDate(self.command.date)) : \(self.command.periode_debut) - \(self.command.periode_fin)"
        
        self.stateCommandLabel.text = "Etat : \(self.command.state)"
        
    }
    
    


}
