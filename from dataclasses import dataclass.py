from dataclasses import dataclass


import random

@dataclass
class Piece:
    machine:str
    defectueuse:bool

def gen_piece():
    
    piece = Piece()
    rand = random.randint()
    if(rand <= 0.5):
        if(rand <= 0.03):
            piece = Piece(machine="A", defectueuse=True)
        else: 
            piece = Piece(machine="A", defectueuse=False)
    elif(rand >= .5):
        if(rand > .3):
            piece = Piece(machine="B", defectueuse=True)
        else:
            piece = Piece(machine="B", defectueuse=False)



    return piece

gen_piece()
