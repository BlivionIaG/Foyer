//
//  ViewController.m
//  AppFoyerISEN
//
//  Created by Renald Morice on 15/02/2016.
//  Copyright © 2016 Renald Morice. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];

    self.isenURL = @"https://web.isen-bretagne.fr/cas/login";
    
    NSURLSession *session = [NSURLSession sharedSession];
    
    NSURLSessionTask *task = [session dataTaskWithURL: [NSURL URLWithString:self.isenURL] completionHandler:^(NSData *data, NSURLResponse *response, NSError *error) {
        // handle response
        if(error == nil){
            NSLog(@"response = %@", response);
            NSString * text = [[NSString alloc] initWithData: data encoding: NSUTF8StringEncoding];
            NSLog(@"Data = %@",text);
        }
        else NSLog(@"error : %@", error);
    }];
    
    [task resume];
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


@end
