using app.Models;
using Microsoft.EntityFrameworkCore;

namespace app.Data
{
    public class DataContext : DbContext
    {
        public DataContext(DbContextOptions<DataContext> options) : base(options)
        {

        }
        
        public virtual DbSet<Item> TB_Item { get; set; }
        public virtual DbSet<Config> TB_Config { get; set; }
        public virtual DbSet<Logs_Inventory> TB_Logs_Inventory { get; set; }
        public virtual DbSet<Logs_Config> TB_Logs_Config { get; set; }
        public virtual DbSet<Logs_Prize> TB_Logs_Prize { get; set; }
        public virtual DbSet<Prize_Control> TB_Prize_Control { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Item>().HasData
            (
                new Item() { Id = 1, NameItem = "Product 1",QuantityItem = 8 }
            );
            // modelBuilder.Entity<Item>().HasKey(objeto => objeto.Id);
            // modelBuilder.Entity<Item>().Property(objeto => objeto.Id).ValueGeneratedOnAdd();

            modelBuilder.Entity<Config>().HasData
            (
                new Config() { Id = 1, NameConfig = "endTime",ValueConfig = "20:00" },
                new Config() { Id = 2, NameConfig = "accelerometer",ValueConfig = "60" },
                new Config() { Id = 3, NameConfig = "inventoryTime",ValueConfig = "60" },
                new Config() { Id = 4, NameConfig = "initialTime",ValueConfig = "09:00" },
                new Config() { Id = 5, NameConfig = "byDay",ValueConfig = "0" },
                new Config() { Id = 6, NameConfig = "finalDay",ValueConfig = "" },
                new Config() { Id = 7, NameConfig = "inventoryBy10",ValueConfig = "0" },
                new Config() { Id = 8, NameConfig = "prizeBy10",ValueConfig = "5" }
            );


            modelBuilder.Entity<Prize_Control>().HasData
            (
                new Prize_Control() { Id = 1, NameControl = "prizeByDay", ValueControl = "", DateUpdate = null },
                new Prize_Control() { Id = 2, NameControl = "inventory1", ValueControl = "false", DateUpdate = null },
                new Prize_Control() { Id = 3, NameControl = "prizeInDay", ValueControl = "0", DateUpdate = null }
            );

        }
    }
}
