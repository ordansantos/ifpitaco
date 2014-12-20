package br.edu.ifpb.entidades;


public class Ramo {
	
	int id;
	String ramoNome;
	
	public Ramo(int id, String ramoNome) {
		super();
		this.id= id;
		this.ramoNome = ramoNome;
	}

	public int getId() {
		return id;
	}

	public void setId(int iD) {
		this.id = iD;
	}

	public String getRamoNome() {
		return ramoNome;
	}

	public void setRamoNome(String ramoNome) {
		this.ramoNome = ramoNome;
	}
	
	
	
	
	
}
