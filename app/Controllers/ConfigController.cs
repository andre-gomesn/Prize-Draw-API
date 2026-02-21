using app.Data;
using app.Models;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace app.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class ConfigController : ControllerBase
    {
        private readonly DataContext _context;

        public ConfigController(DataContext context)
        {
            _context = context;
        }

        // id:1 = endTime
        // id:2 = accelerometer
        // id:3 = intentoryTime
        // id:4 = initialTime
        // id:5 = byDay
        // id:6 = finalDay
        // id:7 = inventoryBy10
        // id:8 = prizeBy10

        [HttpPut("{id}")]
        public async Task<IActionResult> UpdateConfigAsync(int id, string newValueConfig)
        {
            try
            {
                var config = await _context.TB_Config.FindAsync(id);
                if (config == null)
                    return NotFound();

                config.ValueConfig = newValueConfig;

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }

        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpPut("/updateTimes/")]
        public async Task<IActionResult> UpdateTimesAsync(string initialTime, string endTime, string dividedByDay, string finalDay)
        {
            try
            {
                var configIds = new[] { 4, 1, 5, 6 }; // IDs for initialTime, endTime, byDay, finalDay
                var configs = await _context.TB_Config.Where(x => configIds.Contains(x.Id)).ToListAsync();

                if (configs.Count != configIds.Length)
                    return NotFound("One or more config records were not found.");

                configs.First(x => x.Id == 4).ValueConfig = initialTime;
                configs.First(x => x.Id == 1).ValueConfig = endTime;
                configs.First(x => x.Id == 5).ValueConfig = dividedByDay;
                configs.First(x => x.Id == 6).ValueConfig = finalDay;

                await _context.SaveChangesAsync();

                int affectedRows = await _context.SaveChangesAsync();
                return Ok(affectedRows);

            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        // [HttpPut("/updateConfigDistribution/")]
        // public async Task<IActionResult> updateConfigDistribution(int typeDistribution, int value)
        // {
        //     try
        //     {
        //         if (typeDistribution == 1)
        //             await UpdateConfigAsync(7, value.ToString());
        //         else
        //             await UpdateConfigAsync(2, value.ToString());
        //         var configs = await _context.TB_Config.Where(x => configIds.Contains(x.Id)).ToListAsync();

        //         if (configs.Count != configIds.Length)
        //             return NotFound("One or more config records were not found.");

        //         configs.First(x => x.Id == 4).ValueConfig = initialTime;
        //         configs.First(x => x.Id == 1).ValueConfig = endTime;
        //         configs.First(x => x.Id == 5).ValueConfig = dividedByDay;
        //         configs.First(x => x.Id == 6).ValueConfig = finalDay;

        //         await _context.SaveChangesAsync();

        //         int affectedRows = await _context.SaveChangesAsync();
        //         return Ok(affectedRows);

        //     }
        //     catch (System.Exception ex)
        //     {
        //         return BadRequest(ex.Message);
        //     }
        // }


        [HttpGet("/byId")]
        public async Task<IActionResult> GetConfigByIdAsync(int id)
        {
            try
            {
                var config = await _context.TB_Config.FindAsync(id);
                if (config == null)
                    return NotFound();

                return Ok(config);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpGet("/getConfigDvidedByDay")]
        public async Task<IActionResult> GetConfigDvidedByDay()
        {
            try
            {
                var config = await _context.TB_Config.FindAsync(5); // id 5 is byDay
                if (config == null)
                    return NotFound();

                return Ok(config.ValueConfig);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpGet("/getConfigLastDay")]
        public async Task<IActionResult> GetConfigLastDay()
        {
            try
            {
                var config = await _context.TB_Config.FindAsync(6); // id 6 is finalDay
                if (config == null)
                    return NotFound();

                return Ok(config.ValueConfig);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpGet("/getConfigEndTime")]
        public async Task<IActionResult> GetEndTime()
        {
            try
            {
                var config = await _context.TB_Config.FindAsync(1); // id 1 is endTime
                if (config == null)
                    return NotFound();

                return Ok(config.ValueConfig);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpGet("/getConfigInitialTime")]
        public async Task<IActionResult> GetInitialTime()
        {
            try
            {
                var config = await _context.TB_Config.FindAsync(4); // id 4 is initialTime
                if (config == null)
                    return NotFound();

                return Ok(config.ValueConfig);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        [HttpGet]
        public async Task<IActionResult> GetAllConfigs()
        {
            try
            {
                List<Config> list = await _context.TB_Config.ToListAsync();
                return Ok(list);
            }
            catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            }
        }


        // [ApiExplorerSettings(IgnoreApi = true)]
        [HttpGet("/logConfigChange")]
        public async Task<IActionResult> SaveLogConfigChangeAsync()
        {
            try{
                //get the list of current configs and items quantity
                var currentConfigs = await _context.TB_Config.ToListAsync();
                var currentItemsArray = await _context.TB_Item.Select(x=>x.QuantityItem).ToListAsync();
                
                // get type of distribution
                var distribution = currentConfigs.First(c => c.NameConfig == "inventoryBy10").ValueConfig=="1" ? "by10" : "accelerometer";

                // get time interval if distribution is not by10
                var timeInterval = distribution=="by10" ? null : string.Join(",",currentConfigs.First(c => c.NameConfig == "endTime").ValueConfig, currentConfigs.First(c => c.NameConfig == "initialTime").ValueConfig);

                Logs_Config logConfig = new Logs_Config
                {
                    ItemsQuantity = string.Join(",", currentItemsArray),
                    DistributionType = distribution,
                    TimeInterval = timeInterval,
                    SpeedDistribution = currentConfigs.First(c => c.NameConfig == "accelerometer").ValueConfig,
                    DividedDay = currentConfigs.First(c => c.NameConfig == "byDay").ValueConfig=="1" ? currentConfigs.First(c => c.NameConfig == "finalDay").ValueConfig : null,
                    DistributedInTen = distribution=="by10" ? int.Parse(currentConfigs.First(c => c.NameConfig == "prizeBy10").ValueConfig) : null
                };

                await _context.TB_Logs_Config.AddAsync(logConfig);
                await _context.SaveChangesAsync();
                return Ok();

            }catch (System.Exception ex)
            {
                return BadRequest(ex.Message);
            } 
        }
        
        // public async int IntentoryLogic(){
            
        // }
    }
}
